<?php

namespace App\Entity;

use App\Repository\SeoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeoRepository::class)]
class Seo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 90)]
    private ?string $url = null;

    #[ORM\Column(type: Types::TEXT, length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\OneToMany(targetEntity: SeoTag::class, mappedBy: 'seo', cascade: ['persist', 'remove'])]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, SeoTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(SeoTag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->setSeo($this);
        }

        return $this;
    }

    public function removeTag(SeoTag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            // set the owning side to null (unless already changed)
            if ($tag->getSeo() === $this) {
                $tag->setSeo(null);
            }
        }

        return $this;
    }
}
