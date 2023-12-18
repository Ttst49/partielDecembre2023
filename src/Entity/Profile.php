<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[Groups(["forEventIndexing","forUserIndexing","forInvitationPurpose","forGroupIndexing"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forEventIndexing","forUserIndexing","forInvitationPurpose","forGroupIndexing"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $displayName = null;


    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $associatedTo = null;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: Event::class, orphanRemoval: true)]
    private Collection $events;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'participants')]
    private Collection $eventsAsParticipant;

    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Invitation::class)]
    private Collection $invitationsAsRecipient;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Invitation::class)]
    private Collection $invitationsAsSender;

    #[ORM\OneToMany(mappedBy: 'supportedBy', targetEntity: Suggestion::class)]
    private Collection $suggestions;

    #[ORM\OneToMany(mappedBy: 'supportedBy', targetEntity: SupportedStandalone::class)]
    private Collection $supportedStandalones;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->eventsAsParticipant = new ArrayCollection();
        $this->invitationsAsRecipient = new ArrayCollection();
        $this->invitationsAsSender = new ArrayCollection();
        $this->suggestions = new ArrayCollection();
        $this->supportedStandalones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getAssociatedTo(): ?User
    {
        return $this->associatedTo;
    }

    public function setAssociatedTo(User $associatedTo): static
    {
        $this->associatedTo = $associatedTo;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setHost($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getHost() === $this) {
                $event->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventsAsParticipant(): Collection
    {
        return $this->eventsAsParticipant;
    }

    public function addEventsAsParticipant(Event $eventsAsParticipant): static
    {
        if (!$this->eventsAsParticipant->contains($eventsAsParticipant)) {
            $this->eventsAsParticipant->add($eventsAsParticipant);
            $eventsAsParticipant->addParticipant($this);
        }

        return $this;
    }

    public function removeEventsAsParticipant(Event $eventsAsParticipant): static
    {
        if ($this->eventsAsParticipant->removeElement($eventsAsParticipant)) {
            $eventsAsParticipant->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitationsAsRecipient(): Collection
    {
        return $this->invitationsAsRecipient;
    }

    public function addInvitationsAsRecipient(Invitation $invitationsAsRecipient): static
    {
        if (!$this->invitationsAsRecipient->contains($invitationsAsRecipient)) {
            $this->invitationsAsRecipient->add($invitationsAsRecipient);
            $invitationsAsRecipient->setRecipient($this);
        }

        return $this;
    }

    public function removeInvitationsAsRecipient(Invitation $invitationsAsRecipient): static
    {
        if ($this->invitationsAsRecipient->removeElement($invitationsAsRecipient)) {
            // set the owning side to null (unless already changed)
            if ($invitationsAsRecipient->getRecipient() === $this) {
                $invitationsAsRecipient->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitationsAsSender(): Collection
    {
        return $this->invitationsAsSender;
    }

    public function addInvitationsAsSender(Invitation $invitationsAsSender): static
    {
        if (!$this->invitationsAsSender->contains($invitationsAsSender)) {
            $this->invitationsAsSender->add($invitationsAsSender);
            $invitationsAsSender->setSender($this);
        }

        return $this;
    }

    public function removeInvitationsAsSender(Invitation $invitationsAsSender): static
    {
        if ($this->invitationsAsSender->removeElement($invitationsAsSender)) {
            // set the owning side to null (unless already changed)
            if ($invitationsAsSender->getSender() === $this) {
                $invitationsAsSender->setSender(null);
            }
        }

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
            $suggestion->setSupportedBy($this);
        }

        return $this;
    }

    public function removeSuggestion(Suggestion $suggestion): static
    {
        if ($this->suggestions->removeElement($suggestion)) {
            // set the owning side to null (unless already changed)
            if ($suggestion->getSupportedBy() === $this) {
                $suggestion->setSupportedBy(null);
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
            $supportedStandalone->setSupportedBy($this);
        }

        return $this;
    }

    public function removeSupportedStandalone(SupportedStandalone $supportedStandalone): static
    {
        if ($this->supportedStandalones->removeElement($supportedStandalone)) {
            // set the owning side to null (unless already changed)
            if ($supportedStandalone->getSupportedBy() === $this) {
                $supportedStandalone->setSupportedBy(null);
            }
        }

        return $this;
    }
}
