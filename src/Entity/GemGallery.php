<?php

namespace App\Entity;

use App\Repository\GemGalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GemGalleryRepository::class)]
class GemGallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?bool $isPublic = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Pierre>
     */
    #[ORM\ManyToMany(targetEntity: Pierre::class, inversedBy: 'gemGalleries')]
    private Collection $pierres;

    #[ORM\ManyToOne(inversedBy: 'gemGalleries')]
    private ?Member $creator = null;

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

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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
        }

        return $this;
    }

    public function removePierre(Pierre $pierre): static
    {
        $this->pierres->removeElement($pierre);

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
