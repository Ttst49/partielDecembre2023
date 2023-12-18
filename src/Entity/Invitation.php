<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvitationRepository::class)]
class Invitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invitationsAsRecipient')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $recipient = null;

    #[ORM\ManyToOne(inversedBy: 'invitationsToEvent')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $toEvent = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?InvitationStatus $status = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipient(): ?Profile
    {
        return $this->recipient;
    }

    public function setRecipient(?Profile $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getToEvent(): ?Event
    {
        return $this->toEvent;
    }

    public function setToEvent(?Event $toEvent): static
    {
        $this->toEvent = $toEvent;

        return $this;
    }

    public function getStatus(): ?InvitationStatus
    {
        return $this->status;
    }

    public function setStatus(?InvitationStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
    
}
