<?php

namespace App\Entity;

use App\Repository\PierreRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PierreRepository::class)]
#[Vich\Uploadable]
class Pierre
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
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

    #[Vich\UploadableField(mapping: 'pierres', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null; // Le champ qui contient le fichier téléchargé

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null; // Nom du fichier dans la base de données

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null; // Taille du fichier

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null; // Date de mise à jour de l'image

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // La mise à jour de la date déclenche l'événement de VichUploader pour sauvegarder l'image
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


    /**
     * @var Collection<int, GemGallery>
     */
    #[ORM\ManyToMany(targetEntity: GemGallery::class, mappedBy: 'pierres')]
    private Collection $gemGalleries;

    public function __construct()
    {
        $this->gemGalleries = new ArrayCollection();
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

    /**
     * @return Collection<int, GemGallery>
     */
    public function getGemGalleries(): Collection
    {
        return $this->gemGalleries;
    }

    public function addGemGallery(GemGallery $gemGallery): static
    {
        if (!$this->gemGalleries->contains($gemGallery)) {
            $this->gemGalleries->add($gemGallery);
            $gemGallery->addPierre($this);
        }

        return $this;
    }

    public function removeGemGallery(GemGallery $gemGallery): static
    {
        if ($this->gemGalleries->removeElement($gemGallery)) {
            $gemGallery->removePierre($this);
        }

        return $this;
    }
}
