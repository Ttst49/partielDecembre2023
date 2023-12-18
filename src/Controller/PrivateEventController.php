<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Invitation;
use App\Entity\Profile;
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
     * get all the contributions reunited (suggestions and supported)
     */
    #[Route('/private/event/showContributions/{id}',methods: "GET")]
    public function showContributions(Event $event):Response{
        $response = [
            "content"=>"There are all the contributions in this private event",
            "status"=>200,
            "suggestions" => $event->getSuggestions(),
            "supported"=>$event->getSupported()
        ];


        return $this->json($response,200,[],["groups"=>"forGroupIndexing"]);
    }





}
