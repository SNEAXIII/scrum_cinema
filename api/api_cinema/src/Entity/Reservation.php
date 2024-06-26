<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[Groups(['create_reservation'])]
    #[ORM\Column]
    private ?int $nombrePlaces = null;

    #[Groups(['create_reservation'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $dateReservation = null;

    #[Groups(['create_reservation'])]
    #[ORM\Column]
    private ?float $montantTotal = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $reservePar = null;

    #[Groups(['create_reservation'])]
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seance $seance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombrePlaces(): ?int
    {
        return $this->nombrePlaces;
    }

    public function setNombrePlaces(int $nombrePlaces): static
    {
        $this->nombrePlaces = $nombrePlaces;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeImmutable
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeImmutable $dateReservation): static
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(float $montantTotal): static
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    public function getReservePar(): ?User
    {
        return $this->reservePar;
    }

    public function setReservePar(?User $reservePar): static
    {
        $this->reservePar = $reservePar;

        return $this;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $seance): static
    {
        $this->seance = $seance;

        return $this;
    }

//    public function getUser(): ?User
//    {
//        return $this->user;
//    }
//
//    public function setUser(?User $user): static
//    {
//        $this->user = $user;
//
//        return $this;
//    }
}
