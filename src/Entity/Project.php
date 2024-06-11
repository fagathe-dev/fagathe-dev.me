<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['website_data'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Ce champs est obligatoire !')]
    #[Groups(['website_data'])]
    private ?string $name = null;
    
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['website_data'])]
    private ?string $description = null;
    
    #[ORM\Column(nullable: true)]
    #[Groups(['website_data'])]
    private ?string $image = null;
    
    #[ORM\Column]
    #[Groups(['website_data'])]
    private ?\DateTimeImmutable $createdAt = null;
    
    #[ORM\Column(nullable: true)]
    #[Groups(['website_data'])]
    private ?\DateTimeImmutable $updatedAt = null;
    
    #[ORM\Column(length: 15)]
    #[Assert\NotBlank(message: 'Ce champs est obligatoire !')]
    #[Groups(['website_data'])]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['website_data'])]
    private ?array $tasks = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['website_data'])]
    private ?bool $isPublished = null;

    public function __construct()
    {
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of image
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */
    public function setImage($image): static
    {
        $this->image = $image;

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

    public function getTasks(): ?array
    {
        if (is_null($this->tasks)) {
            $this->tasks = [''];

            return $this->tasks;
        }

        return $this->tasks;
    }

    public function setTasks(?array $tasks): static
    {
        $this->tasks = $tasks;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }
}
