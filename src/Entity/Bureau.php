<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "bureau")]
class Bureau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "integer")]
    private $nombPlace;

    #[ORM\Column(type: "string", length: 5)]
    private $n_bureau;

    /**
     * @ORM\OneToMany(targetEntity: Personne::class, mappedBy: "bureau")
     */
    private Collection $personnes;

    public function __construct()
    {
        $this->personnes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombPlace(): ?int
    {
        return $this->nombPlace;
    }

    public function setNombPlace(int $nombPlace): self
    {
        $this->nombPlace = $nombPlace;

        return $this;
    }

    public function getNBureau(): ?string
    {
        return $this->n_bureau;
    }

    public function setNBureau(string $n_bureau): self
    {
        $this->n_bureau = $n_bureau;

        return $this;
    }

    public function getPersonnes(): Collection
    {
        return $this->personnes;
    }
}
