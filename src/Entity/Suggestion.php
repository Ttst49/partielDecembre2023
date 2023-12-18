<?php

namespace App\Entity;

use App\Repository\SuggestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SuggestionRepository::class)]
class Suggestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forGroupIndexing"])]
    #[ORM\Column]
    private ?bool $isSupported = null;

    #[Groups(["forGroupIndexing"])]
    #[ORM\ManyToOne(inversedBy: 'suggestions')]
    private ?Profile $supportedBy = null;

    #[Groups(["forGroupIndexing"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\OneToOne(mappedBy: 'associatedToSuggestion', cascade: ['persist', 'remove'])]
    private ?Supported $supported = null;

    #[ORM\ManyToOne(inversedBy: 'suggestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $associatedEvent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsSupported(): ?bool
    {
        return $this->isSupported;
    }

    public function setIsSupported(bool $isSupported): static
    {
        $this->isSupported = $isSupported;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSupported(): ?Supported
    {
        return $this->supported;
    }

    public function setSupported(?Supported $supported): static
    {
        // unset the owning side of the relation if necessary
        if ($supported === null && $this->supported !== null) {
            $this->supported->setAssociatedToSuggestion(null);
        }

        // set the owning side of the relation if necessary
        if ($supported !== null && $supported->getAssociatedToSuggestion() !== $this) {
            $supported->setAssociatedToSuggestion($this);
        }

        $this->supported = $supported;

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
}
