<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Member implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Ecrin $relation = null;

    /**
     * @var Collection<int, GemGallery>
     */
    #[ORM\OneToMany(targetEntity: GemGallery::class, mappedBy: 'Creator')]
    private Collection $gemGalleries;

    public function __construct()
    {
        $this->gemGalleries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRelation(): ?Ecrin
    {
        return $this->relation;
    }

    public function setRelation(?Ecrin $relation): static
    {
        $this->relation = $relation;

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
            $gemGallery->setCreator($this);
        }

        return $this;
    }

    public function removeGemGallery(GemGallery $gemGallery): static
    {
        if ($this->gemGalleries->removeElement($gemGallery)) {
            // set the owning side to null (unless already changed)
            if ($gemGallery->getCreator() === $this) {
                $gemGallery->setCreator(null);
            }
        }

        return $this;
    }
}
