<?php

namespace App\Entity;

use App\Enum\TypeExperienceEnum;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['website_data', 'read_xp'])]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire !', allowNull: true)]
    #[Groups(['website_data', 'read_xp'])]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    #[Groups(['website_data', 'read_xp'])]
    private ?string $type = null;

    #[ORM\Column(length: 4)]
    #[Groups(['website_data', 'read_xp'])]
    private ?string $start_year = null;

    #[ORM\Column(length: 4, nullable: true)]
    #[Groups(['website_data', 'read_xp'])]
    private ?string $end_year = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['website_data', 'read_xp'])]
    private ?array $tasks = [];

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['website_data', 'read_xp'])]
    #[Assert\NotBlank(message: 'Le lieu est obligatoire !', allowNull: true)]
    private ?string $place = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['website_data', 'read_xp'])]
    private ?bool $published = null;
    
    #[Groups(['website_data', 'read_xp'])]
    private ?string $niceType = null;

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
    
    public function getNiceType(): ?string
    {
        $this->niceType = $this->type === null ? null : TypeExperienceEnum::match($this->type);

        return $this->niceType;
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

    public function addTask(string $task): static
    {
        $this->tasks = [...$this->tasks, $task];

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

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): static
    {
        $this->published = $published;

        return $this;
    }
}
