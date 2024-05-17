<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $nom = null;

    #[ORM\Column(length: 20)]
    private ?string $prenom = null;

    #[ORM\Column(length: 20)]
    private ?string $numero_tel = null;

    #[ORM\Column(type: "date")]
    private ?\DateTimeInterface $date_arrive = null;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $date_depart = null;

    #[ORM\Column(length: 20)]
    /**
     * @Assert\Regex(
     *     pattern="/^(titulaire|ATER|Doctorant|Post-doctorant|Ingénieur|Secrétaire)$/",
     *     message="Le statut doit être 'titulaire', 'ATER', 'Doctorant', 'Post-doctorant', 'Ingénieur' ou 'Secrétaire'"
     * )
     */
    private ?string $statut = null;

    #[ORM\ManyToOne(targetEntity: Bureau::class, inversedBy: 'personnes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Bureau $bureau = null;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumeroTel(): ?string
    {
        return $this->numero_tel;
    }

    public function setNumeroTel(string $numero_tel): static
    {
        $this->numero_tel = $numero_tel;

        return $this;
    }

    public function getDateArrive(): ?\DateTimeInterface
    {
        return $this->date_arrive;
    }

    public function setDateArrive(\DateTimeInterface $date_arrive): static
    {
        $this->date_arrive = $date_arrive;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(?\DateTimeInterface $date_depart): static
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getBureau(): ?Bureau
    {
        return $this->bureau;
    }

    public function setBureau(?Bureau $bureau): static
    {
        $this->bureau = $bureau;

        return $this;
    }
}