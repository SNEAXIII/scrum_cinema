<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 70)]
    private ?string $email = null;

    #[ORM\Column(length: 40)]
    private ?string $motDePasse = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'reservePar')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this -> id;
    }

    public function getEmail(): ?string
    {
        return $this -> email;
    }

    public function setEmail(string $email): static
    {
        $this -> email = $email;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this -> motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this -> motDePasse = $motDePasse;

        return $this;
    }

    public function getRoles(): array
    {
        return $this -> roles;
    }

    public function setRoles(array $roles): static
    {
        $this -> roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setReservePar($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getReservePar() === $this) {
                $reservation->setReservePar(null);
            }
        }

        return $this;
    }
}
