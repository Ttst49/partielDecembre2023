<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{

    /**
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $manager
     * @return Response
     * Create a new user in the application and define to it a new profile
     */
    #[Route('/register', methods: "POST")]
    public function registerApi(SerializerInterface $serializer,Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $manager): Response
    {

        $user = $serializer->deserialize($request->getContent(), User::class, "json");

        $parameters = json_decode($request->getContent(), true);

        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $parameters["password"]
            )
        );

        $user->setProfile(new Profile());
        $user->getProfile()->setDisplayName($user->getUsername());


        $manager->persist($user);
        $manager->flush();

        $response = [
            "content"=> "The user ".$user->getProfile()->getDisplayName()." has been created",
            "status"=>201,
            "user"=>$user
        ];

        return $this->json($response, 201, [], ["groups" => "forUserIndexing"]);

    }
}
