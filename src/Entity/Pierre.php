<?php

namespace App\Entity;

use App\Repository\PierreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PierreRepository::class)]
class Pierre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $typeDePierre = null;

    #[ORM\Column]
    private ?float $poids = null;

    #[ORM\Column(nullable: true)]
    private ?float $valeurEstimee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAcquisition = null;

    #[ORM\ManyToOne(inversedBy: 'pierres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ecrin $ecrin = null;

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

    public function getTypeDePierre(): ?string
    {
        return $this->typeDePierre;
    }

    public function setTypeDePierre(string $typeDePierre): static
    {
        $this->typeDePierre = $typeDePierre;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getValeurEstimee(): ?float
    {
        return $this->valeurEstimee;
    }

    public function setValeurEstimee(?float $valeurEstimee): static
    {
        $this->valeurEstimee = $valeurEstimee;

        return $this;
    }

    public function getDateAcquisition(): ?\DateTimeInterface
    {
        return $this->dateAcquisition;
    }

    public function setDateAcquisition(\DateTimeInterface $dateAcquisition): static
    {
        $this->dateAcquisition = $dateAcquisition;

        return $this;
    }

    public function getEcrin(): ?Ecrin
    {
        return $this->ecrin;
    }

    public function setEcrin(?Ecrin $ecrin): static
    {
        $this->ecrin = $ecrin;

        return $this;
    }
}
