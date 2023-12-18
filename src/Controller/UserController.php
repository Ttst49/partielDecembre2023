<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class UserController extends AbstractController
{
    /**
     * @param UserRepository $repository
     * @return Response
     * Give all the user registered in the application
     */
    #[Route('/showUsers', methods: "GET")]
    public function index(UserRepository $repository): Response
    {
        $response = [
            "content"=>"There are the users",
            "status"=>200,
            "users"=>$repository->findAll(),
        ];

        return $this->json($response,200,[],["groups"=>"forUserIndexing"]);
    }
}
