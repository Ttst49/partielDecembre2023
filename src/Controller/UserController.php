<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Repository\InvitationStatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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



    #[Route('/invitations/index',methods: "GET")]
    public function getInvitations(Request $request):Response{


        $response = [
            "content"=>"There are all the received invitations of current user",
            "status"=>200,
            "invitations"=>$this->getUser()->getProfile()->getInvitationsAsRecipient()
        ];


        return $this->json($response,200,[],["groups"=>"forInvitationPurpose"]);
    }

    #[Route('/invitations/accept/{id}', methods: "PUT")]
    public function acceptInvitation(Invitation $invitation,
                                     InvitationStatusRepository $repository,
                                     EntityManagerInterface $manager):Response{

        $response = [
            "content"=>"You accepted the invitation, see you there!",
            "status"=>200,
            "invitation"=>$invitation
        ];


        if ($invitation->getStatus()->getName() == "Accepted"){
            $response["content"] = "You already accepted this one!";
        }


        if ($invitation->getToEvent()->getstartOn() < new \DateTime()){
            $response = [
                "content"=>"This event is not available, since it already started",
                "status"=>200,
            ];
            $invitation->setStatus($repository->find(3));
        }else{
            $invitation->setStatus($repository->find(2));
        }

        $manager->persist($invitation);
        $manager->flush();
        return $this->json($response,200,[],["groups"=>"forInvitationPurpose"]);
    }



    #[Route('/invitations/deny/{id}',methods: "PUT")]
    public function denyInvitation(Invitation $invitation,
                                   InvitationStatusRepository $repository,
                                   EntityManagerInterface $manager):Response{
        $response = [
            "content"=>"You denied the invitation, sad to see you leave!",
            "status"=>200,
            "invitation"=>$invitation
        ];

        if ($invitation->getStatus()->getName() == "Refused"){
            $response["content"] = "You already denied this one!";
        }


        if ($invitation->getToEvent()->getstartOn() < new \DateTime()){
            $response = [
                "content"=>"This event is not available, since it already started",
                "status"=>200,
            ];
        }

        $invitation->setStatus($repository->find(3));

        $manager->persist($invitation);
        $manager->flush();
        return $this->json($response,200);
    }

}
