<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"security" = "is_granted('ROLE_USER')"},
 * )
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 */
class Photo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite"
     * })
     */
    private $cheminAcces;

    /**
     * @ORM\Column(type="integer")
     * @Groups({
     *     "annonce:get",
     *     "annonce:get:lite"
     * })
     */
    private $ordre;

    /**
     * @ORM\ManyToOne(targetEntity=Annonce::class, inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annonce;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheminAcces(): ?string
    {
        return $this->cheminAcces;
    }

    public function setCheminAcces(string $cheminAcces): self
    {
        $this->cheminAcces = $cheminAcces;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }
}
