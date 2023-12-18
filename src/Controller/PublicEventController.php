<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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
    public function attendToEvent(Event $event):Response{

        $response = [
            "content"=>"You're the host of this event, you can't attend to it!",
            "status"=>200,
        ];

        if ($event->getHost() != $this->getUser()->getProfile()){
            $event->addParticipant($this->getUser()->getProfile());
            $response["content"] = "You're now attending to the event".$event->getId();
        }

        foreach ($event->getParticipants() as $participant){
            if ($participant== $this->getUser()->getProfile()){
                $response["content"] = "You already are attending to this event!";
            }
        }

        return $this->json($response,200);

    }

}
