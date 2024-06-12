<?php

namespace App\Entity;

use App\Enum\LevelSkillEnum;
use App\Enum\TypeSkillEnum;
use App\Repository\SkillRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['website_data'])]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire !')]
    #[Groups(['website_data'])]
    private ?string $name = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['website_data'])]
    private ?string $level = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['website_data'])]
    #[Assert\NotBlank(message: 'Veuillez sélectionner une valeur !')]
    private ?string $type = null;

    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire !', allowNull: true)]
    #[Groups(['website_data'])]
    private ?string $logo = null;


    #[Groups(['website_data'])]
    private ?string $niceType = null;

    #[Groups(['website_data'])]
    private ?string $niceLevel = null;

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

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getNiceLevel(): ?string
    {
        $this->niceLevel = $this->level === null ? null : LevelSkillEnum::match($this->level);

        return $this->niceLevel;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getNiceType(): ?string
    {
        $this->niceType = $this->type === null ? null : TypeSkillEnum::match($this->type);
        return $this->niceType;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }
}
