<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={"security" = "is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "post",
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"adresse:get:collection"}
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
 *          "groups"={"adresse:get"}
 *     }
 * )
 * @ORM\Entity(repositoryClass=AdresseRepository::class)
 */
class Adresse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *     "adresse:get",
     *     "adresse:get:collection",
     *     "garage:get",
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({
     *     "adresse:get",
     *     "adresse:get:collection"
     * })
     * @Assert\NotNull(
     *     message="Champ obligatoire"
     * )
     * @Assert\Length(
     *     min = 5,
     *     max = 255,
     *     minMessage="5 charactères minimum",
     *     maxMessage="255 charactère maximum"
     * )
     */
    private $ligne1;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({
     *     "adresse:get",
     *     "adresse:get:collection"
     * })
     * @Assert\Length(
     *     min = 1,
     *     max = 255,
     *     minMessage="1 charactères minimum",
     *     maxMessage="255 charactère maximum"
     * )
     */
    private $ligne2;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({
     *     "adresse:get",
     *     "adresse:get:collection"
     * })
     * @Assert\Length(
     *     min = 1,
     *     max = 255,
     *     minMessage="1 charactères minimum",
     *     maxMessage="255 charactère maximum"
     * )
     */
    private $ligne3;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({
     *     "adresse:get",
     *     "adresse:get:collection"
     * })
     * @Assert\NotNull(
     *     message="Champ obligatoire"
     * )
     * @Assert\Length(
     *     min = 5,
     *     max = 5,
     *     exactMessage="5 charactères minimum",
     * )
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *     "adresse:get",
     *     "adresse:get:collection"
     * })
     * @Assert\NotNull(
     *     message="Champ obligatoire"
     * )
     * @Assert\Length(
     *     min = 1,
     *     max = 255,
     *     minMessage="1 charactères minimum",
     *     maxMessage="255 charactère maximum"
     * )
     */
    private $commune;

    /**
     * @ORM\OneToMany(targetEntity=Garage::class, mappedBy="adresse")
     * @Groups({
     *     "adresse:get"
     * })
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

    public function getLigne1(): ?string
    {
        return $this->ligne1;
    }

    public function setLigne1(string $ligne1): self
    {
        $this->ligne1 = $ligne1;

        return $this;
    }

    public function getLigne2(): ?string
    {
        return $this->ligne2;
    }

    public function setLigne2(?string $ligne2): self
    {
        $this->ligne2 = $ligne2;

        return $this;
    }

    public function getLigne3(): ?string
    {
        return $this->ligne3;
    }

    public function setLigne3(?string $ligne3): self
    {
        $this->ligne3 = $ligne3;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getCommune(): ?string
    {
        return $this->commune;
    }

    public function setCommune(string $commune): self
    {
        $this->commune = $commune;

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
            $garage->setAdresse($this);
        }

        return $this;
    }

    public function removeGarage(Garage $garage): self
    {
        if ($this->garages->removeElement($garage)) {
            // set the owning side to null (unless already changed)
            if ($garage->getAdresse() === $this) {
                $garage->setAdresse(null);
            }
        }

        return $this;
    }
}
