<?php

namespace App\Entity;

use App\Repository\GemCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GemCollectionRepository::class)]
class GemCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    private ?Member $owner = null;

    /** @var Collection<int, Gemstone> */
    #[ORM\OneToMany(targetEntity: Gemstone::class, mappedBy: 'collection', orphanRemoval: true)]
    private Collection $gemstones;

    public function __construct()
    {
        $this->gemstones = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getOwner(): ?Member
    {
        return $this->owner;
    }

    public function setOwner(?Member $owner): static
    {
        $this->owner = $owner;
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
            $gemstone->setCollection($this);
        }
        return $this;
    }

    public function removeGemstone(Gemstone $gemstone): static
    {
        if ($this->gemstones->removeElement($gemstone)) {
            if ($gemstone->getCollection() === $this) {
                $gemstone->setCollection(null);
            }
        }
        return $this;
    }
}
