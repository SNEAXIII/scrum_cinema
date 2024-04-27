<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['show_film'])]
    private ?\DateTimeInterface $dateProjection = null;

    #[ORM\Column]
    #[Groups(['show_film'])]
    private ?float $tarifNormal = null;

    #[ORM\Column]
    #[Groups(['show_film'])]
    private ?float $tarifReduit = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'seance')]
    private Collection $reservations;

    #[ORM\ManyToOne(inversedBy: 'seance')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['show_film'])]
    private ?Salle $salle = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Film $film = null;

    public function __construct()
    {
        $this -> reservations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this -> id;
    }

    public function getDateProjection(): ?\DateTimeInterface
    {
        return $this -> dateProjection;
    }

    public function setDateProjection(\DateTimeInterface $dateProjection): static
    {
        $this -> dateProjection = $dateProjection;

        return $this;
    }

    public function getTarifNormal(): ?float
    {
        return $this -> tarifNormal;
    }

    public function setTarifNormal(float $tarifNormal): static
    {
        $this -> tarifNormal = $tarifNormal;

        return $this;
    }

    public function getTarifReduit(): ?float
    {
        return $this -> tarifReduit;
    }

    public function setTarifReduit(float $tarifReduit): static
    {
        $this -> tarifReduit = $tarifReduit;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this -> reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this -> reservations -> contains($reservation)) {
            $this -> reservations -> add($reservation);
            $reservation -> setSeance($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this -> reservations -> removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation -> getSeance() === $this) {
                $reservation -> setSeance(null);
            }
        }

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this -> salle;
    }

    public function setSalle(?Salle $salle): static
    {
        $this -> salle = $salle;

        return $this;
    }

    public function getFilm(): ?Film
    {
        return $this -> film;
    }

    public function setFilm(?Film $film): static
    {
        $this -> film = $film;

        return $this;
    }
}
