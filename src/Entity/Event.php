<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[Groups(["forEventIndexing"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forEventIndexing"])]
    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[Groups(["forEventIndexing"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Groups(["forEventIndexing"])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $StartOn = null;

    #[Groups(["forEventIndexing"])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endOn = null;

    #[Groups(["forEventIndexing"])]
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

    public function __construct()
    {
        $this->participants = new ArrayCollection();
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

    public function getStartOn(): ?\DateTimeInterface
    {
        return $this->StartOn;
    }

    public function setStartOn(\DateTimeInterface $StartOn): static
    {
        $this->StartOn = $StartOn;

        return $this;
    }

    public function getEndOn(): ?\DateTimeInterface
    {
        return $this->endOn;
    }

    public function setEndOn(\DateTimeInterface $endOn): static
    {
        $this->endOn = $endOn;

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
}
