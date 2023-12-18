<?php

namespace App\Entity;

use App\Repository\SupportedStandaloneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupportedStandaloneRepository::class)]
class SupportedStandalone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'supportedStandalones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $associatedEvent = null;

    #[ORM\ManyToOne(inversedBy: 'supportedStandalones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $supportedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAssociatedEvent(): ?Event
    {
        return $this->associatedEvent;
    }

    public function setAssociatedEvent(?Event $associatedEvent): static
    {
        $this->associatedEvent = $associatedEvent;

        return $this;
    }

    public function getSupportedBy(): ?Profile
    {
        return $this->supportedBy;
    }

    public function setSupportedBy(?Profile $supportedBy): static
    {
        $this->supportedBy = $supportedBy;

        return $this;
    }
}
