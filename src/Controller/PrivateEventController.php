<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Invitation;
use App\Entity\Suggestion;
use App\Entity\SupportedStandalone;
use App\Repository\EventRepository;
use App\Repository\InvitationStatusRepository;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class PrivateEventController extends AbstractController
{
    /**
     * @param ProfileRepository $profileRepository
     * @param EventRepository $eventRepository
     * @return Response
     * get all the private events you're attending to
     */
    #[Route('/private/event/index', methods: "GET")]
    public function getAllPrivateEvents(ProfileRepository $profileRepository, EventRepository $eventRepository): Response
    {
        $profile = $profileRepository->find($this->getUser()->getProfile()->getId());
        $allPrivateEvents = $eventRepository->findBy(["isPrivate"=>true]);
        $events = new ArrayCollection();
        foreach ($allPrivateEvents as $privateEvent){
            foreach ($privateEvent->getParticipants() as $participant){
                if ($participant === $profile){
                    $events->add($privateEvent);
                }
            }
        }

        $response = [
            "content"=>"There are the private events you're attending",
            "status"=>200,
            "events"=>$events
        ];

        return $this->json($response,200,[],["groups"=>"forEventIndexing"]);
    }


    /**
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * create a new private event and define it default values as a private event
     */
    #[Route('/private/event/create',methods: "POST")]
    public function createPrivateEvent(SerializerInterface $serializer,
                                       Request $request,
                                       EntityManagerInterface $manager):Response{

        $newPrivateEvent = $serializer->deserialize($request->getContent(),Event::class,"json");
        $newPrivateEvent->setIsPrivate(true);
        $newPrivateEvent->setHost($this->getUser()->getProfile());
        $newPrivateEvent->addParticipant($this->getUser()->getProfile());

        $response = [
            "content"=>"You created a private Event!",
            "status"=>201,
            "new Event Infos"=>$newPrivateEvent
        ];



        if ($newPrivateEvent->getEndOn() < $newPrivateEvent->getstartOn() ){
            $response = [
                "content"=>"You can't define an end date arriving after the starting date",
                "status"=>403,
            ];
        }elseif ($newPrivateEvent->getstartOn() < new \DateTime()){
            $response = [
                "content"=>"You can't define a starting date before today",
                "status"=>403,
            ];
        }else{
            $manager->persist($newPrivateEvent);
            $manager->flush();
        }


        return $this->json($response,200,[],["groups"=>"forEventIndexing"]);
    }


    /**
     * @param Event $event
     * @param EntityManagerInterface $manager
     * @param UserRepository $userRepository
     * @param InvitationStatusRepository $statusRepository
     * @param $userId
     * @return Response
     * create a new invitation to a private event and send it to a user
     */
    #[Route('/private/event/invite/{id}/{userId}', methods: "POST")]
    public function inviteToPrivateEvent(Event $event,
                                         EntityManagerInterface $manager,
                                         UserRepository $userRepository,
                                         InvitationStatusRepository $statusRepository,
                                         $userId):Response{

        $searchedProfile = $userRepository->find($userId)->getProfile();
        $actualProfile = $userRepository->find($this->getUser())->getProfile();

        if ($event->getHost() !== $actualProfile){
            $response = [
                "content"=>"You're not the owner of this event, you can't invite into It",
                "status"=>403
            ];
            return $this->json($response,403);
        }elseif ($event->getHost() === $searchedProfile){
            $response = [
                "content"=>"You can't invite yourself into a private event",
                "status"=>403
            ];
            return $this->json($response,403);
        }

        foreach ($searchedProfile->getInvitationsAsRecipient() as $invitation){
            if ($invitation->getToEvent()->getHost() === $actualProfile){
                $response = [
                    "content"=>"You already been invited to this event",
                    "status"=>200
                ];
                return $this->json($response,200);
            }
        }


        $invitation = new Invitation();
        $invitation->setRecipient($searchedProfile);
        $invitation->setToEvent($event);
        $invitation->setStatus($statusRepository->find(1));

        $manager->persist($invitation);
        $manager->flush();

        $response = [
            "content"=>"You successfuly created a new invitation to the private event with id ".$event->getId(),
            "status"=>201,
            "invitation"=>$invitation
        ];

        return $this->json($response,201,[],["groups"=>"forInvitationPurpose"]);
    }

    /**
     * @param Event $event
     * @param EntityManagerInterface $manager
     * @return Response
     * change the status of an event between scheduled or canceled
     */
    #[Route('/private/event/changeStatus/{id}',methods: "PUT")]
    public function changeEventStatus(Event $event, EntityManagerInterface $manager):Response{

        $response = [
            "content"=>"Your event has been canceled with success",
            "status"=>200,
            "event"=>$event
        ];

        if ($event->isIsScheduled() === true){
            $event->setIsScheduled(false);
        }else{
            $event->setIsScheduled(true);
            $response["content"] = "Your event has been scheduled again!";
        }

        return $this->json($response,200);
    }


    /**
     * @param Event $event
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * edit an event and it infos
     */
    #[Route('/private/event/edit/{id}',methods: "PUT")]
    public function editPrivateEvent( Event $event,
                                       SerializerInterface $serializer,
                                       Request $request,
                                       EntityManagerInterface $manager):Response{

        $editedPrivateEvent = $serializer->deserialize($request->getContent(),Event::class,"json",["object_to_populate"=>$event]);


        $response = [
            "content"=>"You updated a private Event!",
            "status"=>201,
            "new Event Infos"=>$editedPrivateEvent
        ];



        if ($editedPrivateEvent->getEndOn() < $editedPrivateEvent->getstartOn() ){
            $response = [
                "content"=>"You can't define an end date arriving after the starting date",
                "status"=>403,
            ];
        }elseif ($editedPrivateEvent->getstartOn() < new \DateTime()){
            $response = [
                "content"=>"You can't define a starting date before today",
                "status"=>403,
            ];
        }else{
            $manager->persist($editedPrivateEvent);
            $manager->flush();
        }


        return $this->json($response,200,[],["groups"=>"forEventIndexing"]);
    }


    /**
     * @param Event $event
     * @return Response
     * get all the contributions
     */
    #[Route('/private/event/showContributions/{id}',methods: "GET")]
    public function showContributions(Event $event):Response{
        $response = [
            "content"=>"There are all the contributions in this private event",
            "status"=>200,
            "suggestions" => $event->getSuggestions(),
            "supported"=>$event->getSupportedStandalones()
        ];


        return $this->json($response,200,[],["groups"=>"forGroupIndexing"]);
    }


    /**
     * @param Event $event
     * @return Response
     * get all suggestions for an event
     */
    #[Route('/private/event/showSuggestions/{id}',methods: "GET")]
    public function showSuggestions(Event $event):Response{
        $response = [
            "content"=>"There are all the contributions in this private event",
            "status"=>200,
            "suggestions" => $event->getSuggestions(),
        ];

        return $this->json($response,200,[],["groups"=>"forGroupIndexing"]);
    }


    /**
     * @param Event $event
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * as a host add a suggestion for an event
     */
    #[Route('/private/event/addSuggestion/{id}',methods: "POST")]
    public function addSuggestion(Event $event,
                                  SerializerInterface $serializer,
                                  Request $request,
                                  EntityManagerInterface $manager):Response{

        $response = [
            "content"=>"You're not the host, then you can't add a suggestion, try adding a supported otherwise",
            "status"=>403,
        ];

        if($event->getHost() == $this->getUser()->getProfile()){
            $newSuggestion = $serializer->deserialize($request->getContent(),Suggestion::class,"json");
            $newSuggestion->setIsSupported(false);
            $newSuggestion->setAssociatedEvent($event);

            $manager->persist($newSuggestion);
            $manager->flush();

            $response =[
                "content"=>"You successfully add a suggestion to the event with id ".$event->getId(),
                "status"=>201,
                "suggestion"=>$newSuggestion
            ];
        }


        return $this->json($response,200,[],["groups"=>"forGroupIndexing"]);
    }


    /**
     * @param Suggestion $suggestion
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * support suggestion and transform it into a contribution and stop supporting suggestion
     */
    #[Route('/private/event/stopSupportSuggestion/{id}',methods: "PUT")]
    #[Route('/private/event/supportSuggestion/{id}',methods: "PUT")]
    public function supportSuggestionManagement(Suggestion $suggestion,
                                      EntityManagerInterface $manager,
                                      Request $request):Response{

        $associatedEvent = $suggestion->getAssociatedEvent();

        $response = [
            "content"=>"You made a contribution and transform a suggestion into reality!",
            "status"=>201,
            "suggestions"=>$associatedEvent->getSuggestions(),
            "supports"=>$associatedEvent->getSupportedStandalones()
        ];



        if ($request->get("_route") == "app_privateevent_supportsuggestionmanagement_1"){
            if ($suggestion->getSupportedBy() === false){
                $suggestion->setIsSupported(true);
                $suggestion->setSupportedBy($this->getUser()->getProfile());
            }else{
                $response["content"]= "This suggestion already have a support!";
            }

        }else{
            if ($suggestion->getSupportedBy() == $this->getUser()->getProfile()){
                $suggestion->setIsSupported(false);
                $suggestion->setSupportedBy(null);
                $response["content"] = "You stopped supporting this suggestion!";
            }else{
                $response["content"] = "You seem not to be the one who supported this suggestion";
            }

        }



        $manager->persist($suggestion);
        $manager->flush();


        return $this->json($response,200,[],["groups"=>"forGroupIndexing"]);
    }

    /**
     * @param Event $event
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * add a standalone support to the event
     */
    #[Route('/private/event/addSupport/{id}',methods: "POST")]
    public function addStandaloneSupport(Event $event,
                                         SerializerInterface $serializer,
                                         EntityManagerInterface $manager,
                                         Request $request):Response{


        $response = [
            "content"=>"You're not in this event, so you can't add a support!",
            "status"=>200,
        ];

        $participantsInArray = new ArrayCollection();

        foreach ($event->getParticipants() as $participant){
            $participantsInArray->add($participant);}

        foreach ($participantsInArray as $participantInArray){

            if ($participantInArray === $this->getUser()->getProfile()){
                $newSupport = $serializer->deserialize($request->getContent(),SupportedStandalone::class,"json");
                $newSupport->setSupportedBy($this->getUser()->getProfile());
                $newSupport->setAssociatedEvent($event);

                $manager->persist($newSupport);
                $manager->flush();

                $response["content"] = "You made a contribution to this event";
                $response["support"] = $newSupport;
            }
        }


        return $this->json($response,200,[],["groups"=>"forGroupIndexing"]);
    }


    /**
     * @param SupportedStandalone $standalone
     * @param EntityManagerInterface $manager
     * @return Response
     * remove a support if you created It or if you're hosting the event
     */
    #[Route('/private/event/removeSupport/{id}',methods: "DELETE")]
    public function removeStandaloneSupport(SupportedStandalone $standalone,
                                            EntityManagerInterface $manager):Response{

        $response = [
            "content"=>"You successfully remove this support",
            "status"=>200,
        ];

        if ($standalone->getSupportedBy()->getAssociatedTo()->getProfile() == $this->getUser()->getProfile()
            or $standalone->getAssociatedEvent()->getHost() == $this->getUser()->getProfile()){
            $manager->remove($standalone);
            $manager->flush();
        }else{
            $response["content"]= "You can't remove support you're not hosting or didn't made";
        }


        return $this->json($response,200);
    }



}
