<?php

namespace App\Controller;

use App\Repository\EventRepository;
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
}
