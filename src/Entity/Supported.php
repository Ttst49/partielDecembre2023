<?php

namespace App\Entity;

use App\Repository\SupportedRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SupportedRepository::class)]
class Supported
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forGroupIndexing"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[Groups(["forGroupIndexing"])]
    #[ORM\OneToOne(inversedBy: 'supported', cascade: ['persist', 'remove'])]
    private ?Suggestion $associatedToSuggestion = null;

    #[ORM\ManyToOne(inversedBy: 'supported')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $associatedToEvent = null;

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

    public function getAssociatedToSuggestion(): ?Suggestion
    {
        return $this->associatedToSuggestion;
    }

    public function setAssociatedToSuggestion(?Suggestion $associatedToSuggestion): static
    {
        $this->associatedToSuggestion = $associatedToSuggestion;

        return $this;
    }

    public function getAssociatedToEvent(): ?Event
    {
        return $this->associatedToEvent;
    }

    public function setAssociatedToEvent(?Event $associatedToEvent): static
    {
        $this->associatedToEvent = $associatedToEvent;

        return $this;
    }
}
