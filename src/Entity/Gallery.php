<?php

namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GalleryRepository::class)]
class Gallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true, options: ['default' => true])]
    private ?bool $isPublic = true;

    #[ORM\Column(nullable: true)]
    private ?bool $published = null;

    /** @var Collection<int, Gemstone> */
    #[ORM\ManyToMany(targetEntity: Gemstone::class, inversedBy: 'galleries')]
    private Collection $gemstones;

    #[ORM\ManyToOne(targetEntity: Member::class, inversedBy: 'galleries')]
    private ?Member $creator = null;

    public function __construct()
    {
        $this->gemstones = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? 'Gallery #' . $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
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

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(?bool $isPublic): static
    {
        $this->isPublic = $isPublic;
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

    /** @return Collection<int, Gemstone> */
    public function getGemstones(): Collection
    {
        return $this->gemstones;
    }

    public function addGemstone(Gemstone $gemstone): static
    {
        if (!$this->gemstones->contains($gemstone)) {
            $this->gemstones->add($gemstone);
        }
        return $this;
    }

    public function removeGemstone(Gemstone $gemstone): static
    {
        $this->gemstones->removeElement($gemstone);
        return $this;
    }

    public function getCreator(): ?Member
    {
        return $this->creator;
    }

    public function setCreator(?Member $creator): static
    {
        $this->creator = $creator;
        return $this;
    }
}
