<?php

namespace App\Entity;

use App\Repository\TrackingEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackingEventRepository::class)]
class TrackingEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 60)]
    private ?string $code = null;

    #[ORM\Column(length: 50)]
    private ?string $page = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbRequest = null;

    #[ORM\OneToMany(targetEntity: TrackingEventLog::class, mappedBy: 'trackingEvent')]
    private Collection $logs;

    public function __construct()
    {
        $this->logs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(string $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function getNbRequest(): ?int
    {
        return $this->nbRequest;
    }

    public function setNbRequest(?int $nbRequest): static
    {
        $this->nbRequest = $nbRequest;

        return $this;
    }

    /**
     * @return Collection<int, TrackingEventLog>
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(TrackingEventLog $log): static
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);
            $log->setTrackingEvent($this);
        }

        return $this;
    }

    public function removeLog(TrackingEventLog $log): static
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getTrackingEvent() === $this) {
                $log->setTrackingEvent(null);
            }
        }

        return $this;
    }
}
