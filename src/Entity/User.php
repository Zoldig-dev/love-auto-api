<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"security" = "is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "post" = {"security" = "is_granted('ROLE_ADMIN')"},
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"user:get:lite"},
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              }
 *          }
 *      },
 *     itemOperations={
 *     "get",
 *     "put" = {"security" = "is_granted('ROLE_ADMIN')"},
 *     "delete" = {"security" = "is_granted('ROLE_ADMIN')"},
 *     "patch" = {"security" = "is_granted('ROLE_ADMIN')"},
 * },
 *     normalizationContext={
 *          "groups"={"user:get"}
 *     }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *     "garage:get",
     *     "garage:get:collection",
     *     "user:get",
     *     "user:get:lite",
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({
     *     "garage:get",
     *     "garage:get:collection",
     *     "user:get",
     *     "user:get:lite",
     * })
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({
     *     "user:get",
*     })
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({
     *     "user:get",
     *     })
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({
     *     "user:get",
     *     "user:get:lite",
     *     "garage:get"
     *     })
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({
     *     "user:get",
     *     "user:get:lite",
     *     "garage:get"
     *     })
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Groups({
     *     "user:get",
     *     "user:get:lite",
     *     "garage:get"
     *     })
     */
    private $numTel;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     * @Groups({
     *     "user:get",
     *     })
     */
    private $siret;

    /**
     * @ORM\OneToMany(targetEntity=Garage::class, mappedBy="user", orphanRemoval=true)
     * @Groups({
     *     "user:get",
     *     })
     */
    private $garages;

    public function __construct()
    {
        $this->garages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(?string $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return Collection|Garage[]
     */
    public function getGarages(): Collection
    {
        return $this->garages;
    }

    public function addGarage(Garage $garage): self
    {
        if (!$this->garages->contains($garage)) {
            $this->garages[] = $garage;
            $garage->setUser($this);
        }

        return $this;
    }

    public function removeGarage(Garage $garage): self
    {
        if ($this->garages->removeElement($garage)) {
            // set the owning side to null (unless already changed)
            if ($garage->getUser() === $this) {
                $garage->setUser(null);
            }
        }

        return $this;
    }
}
