<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column(length: 4)]
    private ?string $start_year = null;

    #[ORM\Column(length: 4, nullable: true)]
    private ?string $end_year = null;

    #[ORM\Column(nullable: true)]
    private ?array $tasks = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $place = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
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

    public function getStartYear(): ?string
    {
        return $this->start_year;
    }

    public function setStartYear(string $start_year): static
    {
        $this->start_year = $start_year;

        return $this;
    }

    public function getEndYear(): ?string
    {
        return $this->end_year;
    }

    public function setEndYear(?string $end_year): static
    {
        $this->end_year = $end_year;

        return $this;
    }

    public function getTasks(): ?array
    {
        return $this->tasks;
    }

    public function setTasks(?array $tasks): static
    {
        $this->tasks = $tasks;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): static
    {
        $this->place = $place;

        return $this;
    }
}
