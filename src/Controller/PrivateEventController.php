<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\ProfileRepository;
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
                                       EntityManagerInterface $manager
    ):Response{





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

}
