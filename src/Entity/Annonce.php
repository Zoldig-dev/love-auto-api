<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={"security" = "is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "post",
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"annonce:get:lite"}
 *              }
 *          }
 *      },
 *     itemOperations={
 *     "get",
 *     "put",
 *     "delete",
 *     "patch",
 * },
 *     normalizationContext={
 *          "groups"={"annonce:get"}
 *     }
 * )
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 * @ApiFilter(RangeFilter::class, properties={"kilometrage", "anneeCirculation", "prix"})
 * @ApiFilter(SearchFilter::class, properties={
 *     "typeCarburant.libelle"="exact",
 *     "marque.nom"="exact",
 *     "modele.nom"="exact",
 * })
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite",
     *     "modele:get",
     *     "typeCarburant:get",
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite",
     *     "modele:get",
     *     "garage:get",
     *     "typeCarburant:get",
     * })
     * @Assert\NotNull(
     *     message="Champ obligatoire"
     * )
     * @Assert\Length(
     *     min = 8,
     *     max = 255,
     *     minMessage="8 charactères minimum",
     *     maxMessage="255 charactère maximum"
     * )
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite"
     * })
     * @Assert\NotNull(
     *     message="Champ obligatoire"
     * )
     * @Assert\Length(
     *     min = 30,
     *     max = 3500,
     *     minMessage="30 charactères minimum",
     *     maxMessage="3500 charactère maximum"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite"
     * })
     * @Assert\NotNull(
     *     message="Champ obligatoire"
     * )
     * @Assert\GreaterThanOrEqual(100)
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite"
     * })
     * @Assert\NotNull(
     *     message="Champ obligatoire"
     * )
     * @Assert\GreaterThanOrEqual(100)
     */
    private $kilometrage;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"annonce:get"})
     * @Assert\NotNull(
     *     message="Champ obligatoire"
     * )
     * @Assert\Length(
     *     min = 10,
     *     max = 10,
     *     exactMessage="10 charactères minimum",
     * )
     */
    private $reference;

    /**
     * @ORM\Column(type="integer")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite"
     * })
     * @Assert\NotNull(
     *     message="Champ obligatoire"
     * )
     * @Assert\LessThan("today")
     */
    private $anneeCirculation;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"annonce:get"})
     * @Assert\NotNull(
     *     message="Champ obligatoire"
     * )
     * @Assert\GreaterThanOrEqual(100)
     */
    private $prixEffectifVente;

    /**
     * @ORM\ManyToOne(targetEntity=Modele::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite"
     * })
     */
    private $modele;

    /**
     * @ORM\ManyToOne(targetEntity=TypeCarburant::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite"
     * })
     */
    private $typeCarburant;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="annonce")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite"
     * })
     * @Assert\Count(
     *     min=1,
     *     max=10,
     *     minMessage="Une photo minimum",
     *     maxMessage="Dix photos maximum"
     * )
     */
    private $photos;

    /**
     * @ORM\ManyToOne(targetEntity=Garage::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"annonce:get"})
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
