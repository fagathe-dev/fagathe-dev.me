<?php

namespace App\Entity;

use App\Repository\TrackingEventLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackingEventLogRepository::class)]
class TrackingEventLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $data = null;

    #[ORM\Column(length: 255)]
    private ?string $origin = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $device = null;

    #[ORM\ManyToOne(inversedBy: 'logs')]
    private ?TrackingEvent $trackingEvent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(?string $device): static
    {
        $this->device = $device;

        return $this;
    }

    public function getTrackingEvent(): ?TrackingEvent
    {
        return $this->trackingEvent;
    }

    public function setTrackingEvent(?TrackingEvent $trackingEvent): static
    {
        $this->trackingEvent = $trackingEvent;

        return $this;
    }
}
