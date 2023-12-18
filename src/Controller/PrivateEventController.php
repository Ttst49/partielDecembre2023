<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PrivateEventController extends AbstractController
{
    /**
     * @param ProfileRepository $profileRepository
     * @param EventRepository $eventRepository
     * @return Response
     * get all the private events you're attending to
     */
    #[Route('/private/event/index', name: 'app_private_event')]
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
}
