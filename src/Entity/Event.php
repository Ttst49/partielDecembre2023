<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[Groups(["forEventIndexing","forInvitationPurpose"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forEventIndexing","forInvitationPurpose"])]
    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[Groups(["forEventIndexing","forInvitationPurpose"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Assert\GreaterThanOrEqual('today UTC+3')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startOn = null;

    #[Assert\GreaterThanOrEqual(propertyPath: "startOn")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endOn = null;

    #[Groups(["forEventIndexing","forInvitationPurpose"])]
    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $host = null;

    #[Groups(["forEventIndexing"])]
    #[ORM\Column]
    private ?bool $isPrivate = null;

    #[Groups(["forEventIndexing"])]
    #[ORM\Column]
    private ?bool $isPlacePrivate = null;

    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'eventsAsParticipant')]
    private Collection $participants;

    #[Groups(["forEventIndexing","forInvitationPurpose"])]
    private string $startDateInFormat;

    #[Groups(["forEventIndexing","forInvitationPurpose"])]
    private string $endDateInFormat;

    #[ORM\OneToMany(mappedBy: 'toEvent', targetEntity: Invitation::class, orphanRemoval: true)]
    private Collection $invitationsToEvent;

    #[Groups(["forGroupIndexing"])]
    #[ORM\Column]
    private ?bool $isScheduled = null;

    #[Groups(["forGroupIndexing"])]
    #[ORM\OneToMany(mappedBy: 'associatedEvent', targetEntity: Suggestion::class)]
    private Collection $suggestions;

    #[ORM\OneToMany(mappedBy: 'associatedEvent', targetEntity: SupportedStandalone::class, orphanRemoval: true)]
    private Collection $supportedStandalones;



    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->invitationsToEvent = new ArrayCollection();
        $this->suggestions = new ArrayCollection();
        $this->isScheduled = true;
        $this->supportedStandalones = new ArrayCollection();
        $this->administrators = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }


    public function getstartOn(): ?\DateTimeInterface
    {
        return $this->startOn;
    }

    public function setstartOn(\DateTimeInterface $startOn): static
    {
        $this->startOn = $startOn;
        $this->startDateInFormat = $startOn->format("d/m/y");

        return $this;
    }

    public function getEndOn(): ?\DateTimeInterface
    {
        return $this->endOn;
    }

    public function setEndOn(\DateTimeInterface $endOn): static
    {
        $this->endOn = $endOn;
        $this->endDateInFormat = $endOn->format("d/m/y");

        return $this;
    }

    public function getHost(): ?Profile
    {
        return $this->host;
    }

    public function setHost(?Profile $host): static
    {
        $this->host = $host;

        return $this;
    }

    public function isIsPrivate(): ?bool
    {
        return $this->isPrivate;
    }

    public function setIsPrivate(bool $isPrivate): static
    {
        $this->isPrivate = $isPrivate;

        return $this;
    }

    public function isIsPlacePrivate(): ?bool
    {
        return $this->isPlacePrivate;
    }

    public function setIsPlacePrivate(bool $isPlacePrivate): static
    {
        $this->isPlacePrivate = $isPlacePrivate;

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Profile $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(Profile $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getStartDateInFormat(): string
    {
        return $this->startDateInFormat;
    }


    public function getEndDateInFormat(): string
    {
        return $this->endDateInFormat;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitationsToEvent(): Collection
    {
        return $this->invitationsToEvent;
    }

    public function addInvitationsToEvent(Invitation $invitationsToEvent): static
    {
        if (!$this->invitationsToEvent->contains($invitationsToEvent)) {
            $this->invitationsToEvent->add($invitationsToEvent);
            $invitationsToEvent->setToEvent($this);
        }

        return $this;
    }

    public function removeInvitationsToEvent(Invitation $invitationsToEvent): static
    {
        if ($this->invitationsToEvent->removeElement($invitationsToEvent)) {
            // set the owning side to null (unless already changed)
            if ($invitationsToEvent->getToEvent() === $this) {
                $invitationsToEvent->setToEvent(null);
            }
        }

        return $this;
    }

    public function isIsScheduled(): ?bool
    {
        return $this->isScheduled;
    }

    public function setIsScheduled(bool $isScheduled): static
    {
        $this->isScheduled = $isScheduled;

        return $this;
    }

    /**
     * @return Collection<int, Suggestion>
     */
    public function getSuggestions(): Collection
    {
        return $this->suggestions;
    }

    public function addSuggestion(Suggestion $suggestion): static
    {
        if (!$this->suggestions->contains($suggestion)) {
            $this->suggestions->add($suggestion);
            $suggestion->setAssociatedEvent($this);
        }

        return $this;
    }

    public function removeSuggestion(Suggestion $suggestion): static
    {
        if ($this->suggestions->removeElement($suggestion)) {
            // set the owning side to null (unless already changed)
            if ($suggestion->getAssociatedEvent() === $this) {
                $suggestion->setAssociatedEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SupportedStandalone>
     */
    public function getSupportedStandalones(): Collection
    {
        return $this->supportedStandalones;
    }

    public function addSupportedStandalone(SupportedStandalone $supportedStandalone): static
    {
        if (!$this->supportedStandalones->contains($supportedStandalone)) {
            $this->supportedStandalones->add($supportedStandalone);
            $supportedStandalone->setAssociatedEvent($this);
        }

        return $this;
    }


}
