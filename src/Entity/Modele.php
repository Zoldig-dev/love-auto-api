<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ModeleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={"security" = "is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "post" = {"security" = "is_granted('ROLE_ADMIN')"},
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"modele:get:collection"}
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
 *          "groups"={"modele:get"}
 *     }
 * )
 * @ORM\Entity(repositoryClass=ModeleRepository::class)
 */
class Modele
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite",
     *     "marque:get",
     *     "modele:get",
     *     "modele:get:collection"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite",
     *     "marque:get",
     *     "modele:get",
     *     "modele:get:collection"
     * })
     * @Assert\NotNull(
     *     message="Champ obligatoire"
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 255,
     *     minMessage="2 charactères minimum",
     *     maxMessage="255 charactère maximum"
     * )
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="modele")
     * @Groups({"modele:get"})
     */
    private $annonces;

    /**
     * @ORM\ManyToOne(targetEntity=Marque::class, inversedBy="modeles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite",
     *     "modele:get",
     *     "modele:get:collection"
     * })
     */
    private $marque;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setModele($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getModele() === $this) {
                $annonce->setModele(null);
            }
        }

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }
}
