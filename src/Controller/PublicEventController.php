<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PublicEventController extends AbstractController
{
    /**
     * @return Response
     * get all the public events in the application
     */
    #[Route('/public/event/index', methods: "GET")]
    public function getAllPublicEvents(EventRepository $repository): Response
    {
        $response = [
            "content"=>"There are the public events available",
            "status"=>"200",
            "events"=>$repository->findBy(["isPrivate"=>false])
        ];
        return $this->json($response,200,[],["groups"=>"forEventIndexing"]);

    }


    /**
     * @return Response
     * attending to an event if you're not the host of it
     */
    #[Route('/public/event/attend/{id}', methods: "PUT")]
    public function attendToEvent(Event $event, EntityManagerInterface $manager):Response{

        $response = [
            "content"=>"You're the host of this event, you can't attend to it!",
            "status"=>200,
        ];

        if ($event->getHost() != $this->getUser()->getProfile()){
            $response["content"] = "You're now attending to the event".$event->getId();
            $event->addParticipant($this->getUser()->getProfile());
            $manager->persist($event);
            $manager->flush();
        }

        foreach ($event->getParticipants() as $participant){
            if ($participant== $this->getUser()->getProfile()){
                $response["content"] = "You already are attending to this event!";
            }
        }

        return $this->json($response,200);

    }


    /**
     * @param Event $event
     * @return Response
     * return all the participants from an event with it id
     */
    #[Route('/public/event/getParticipants/{id}')]
    public function getAllParticipantsFromPublicEvent(Event $event):Response{

        $participants = new ArrayCollection();
        foreach ($event->getParticipants() as $participant){
            $participants->add($participant);
        }

        $response = [
            "content"=>"There are the participants to event with id ".$event->getId(),
            "status"=>200,
            "participants"=>$participants
        ];

        return $this->json($response,200,[],["groups"=>"forUserIndexing"]);
    }

}
