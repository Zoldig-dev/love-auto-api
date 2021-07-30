<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MarqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"security" = "is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "post" = {"security" = "is_granted('ROLE_ADMIN')"},
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"marque:get:collection"}
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
 *          "groups"={"marque:get"}
 *     }
 * )
 * @ORM\Entity(repositoryClass=MarqueRepository::class)
 */
class Marque
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *     "annonce:get",
     *      "annonce:get:lite",
     *      "marque:get" ,
     *      "marque:get:collection",
     *     "modele:get",
     *      "modele:get:collection"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *     "annonce:get",
     *      "annonce:get:lite",
     *      "marque:get" ,
     *      "marque:get:collection",
     *      "modele:get",
     *      "modele:get:collection"
     * })
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Modele::class, mappedBy="marque")
     * @Groups({"marque:get"})
     */
    private $modeles;

    public function __construct()
    {
        $this->modeles = new ArrayCollection();
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
     * @return Collection|Modele[]
     */
    public function getModeles(): Collection
    {
        return $this->modeles;
    }

    public function addModele(Modele $modele): self
    {
        if (!$this->modeles->contains($modele)) {
            $this->modeles[] = $modele;
            $modele->setMarque($this);
        }

        return $this;
    }

    public function removeModele(Modele $modele): self
    {
        if ($this->modeles->removeElement($modele)) {
            // set the owning side to null (unless already changed)
            if ($modele->getMarque() === $this) {
                $modele->setMarque(null);
            }
        }

        return $this;
    }
}
