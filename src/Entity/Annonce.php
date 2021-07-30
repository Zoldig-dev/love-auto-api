<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"annonce:get_lite"}
 *              }
 *          }
 *      },
 *     itemOperations={"get"},
 *     normalizationContext={
 *          "groups"={"annonce:get"}
 *     }
 * )
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 * @ApiFilter(RangeFilter::class, properties={"kilometrage", "anneeCirculation", "prix"})
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get_lite",
     *     "modele:get"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *     "annonce:get",
     *     "annonce:get_lite",
     *     "modele:get"
     * })
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get_lite"
     * })
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     * @Groups({
     *     "annonce:get",
     *     "annonce:get_lite"
     * })
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get_lite"
     * })
     */
    private $kilometrage;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"annonce:get"})
     */
    private $reference;

    /**
     * @ORM\Column(type="integer")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get_lite"
     * })
     */
    private $anneeCirculation;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"annonce:get")
     */
    private $prixEffectifVente;

    /**
     * @ORM\ManyToOne(targetEntity=Modele::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({
     *     "annonce:get",
     *     "annonce:get_lite"
     * })
     */
    private $modele;

    /**
     * @ORM\ManyToOne(targetEntity=TypeCarburant::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({
     *     "annonce:get",
     *     "annonce:get_lite"
     * })
     */
    private $typeCarburant;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="annonce")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get_lite"
     * })
     */
    private $photos;

    /**
     * @ORM\ManyToOne(targetEntity=Garage::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"annonce:get")
     */
    private $garage;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(int $kilometrage): self
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getAnneeCirculation(): ?int
    {
        return $this->anneeCirculation;
    }

    public function setAnneeCirculation(int $anneeCirculation): self
    {
        $this->anneeCirculation = $anneeCirculation;

        return $this;
    }

    public function getPrixEffectifVente(): ?string
    {
        return $this->prixEffectifVente;
    }

    public function setPrixEffectifVente(string $prixEffectifVente): self
    {
        $this->prixEffectifVente = $prixEffectifVente;

        return $this;
    }

    public function getModele(): ?Modele
    {
        return $this->modele;
    }

    public function setModele(?Modele $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getTypeCarburant(): ?TypeCarburant
    {
        return $this->typeCarburant;
    }

    public function setTypeCarburant(?TypeCarburant $typeCarburant): self
    {
        $this->typeCarburant = $typeCarburant;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAnnonce($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAnnonce() === $this) {
                $photo->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getGarage(): ?Garage
    {
        return $this->garage;
    }

    public function setGarage(?Garage $garage): self
    {
        $this->garage = $garage;

        return $this;
    }
}
