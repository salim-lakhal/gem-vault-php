<?php

namespace App\Entity;

use App\Repository\EcrinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EcrinRepository::class)]
class Ecrin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDeCreation = null;

    /**
     * @var Collection<int, Pierre>
     */
    #[ORM\OneToMany(targetEntity: Pierre::class, mappedBy: 'ecrin', orphanRemoval: true)]
    private Collection $pierres;

    public function __construct()
    {
        $this->pierres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getDateDeCreation(): ?\DateTimeInterface
    {
        return $this->dateDeCreation;
    }

    public function setDateDeCreation(\DateTimeInterface $dateDeCreation): static
    {
        $this->dateDeCreation = $dateDeCreation;

        return $this;
    }

    /**
     * @return Collection<int, Pierre>
     */
    public function getPierres(): Collection
    {
        return $this->pierres;
    }

    public function addPierre(Pierre $pierre): static
    {
        if (!$this->pierres->contains($pierre)) {
            $this->pierres->add($pierre);
            $pierre->setEcrin($this);
        }

        return $this;
    }

    public function removePierre(Pierre $pierre): static
    {
        if ($this->pierres->removeElement($pierre)) {
            // set the owning side to null (unless already changed)
            if ($pierre->getEcrin() === $this) {
                $pierre->setEcrin(null);
            }
        }

        return $this;
    }
}
