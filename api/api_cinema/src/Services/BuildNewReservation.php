<?php

namespace App\Services;

use App\Entity\Reservation;
use App\Entity\Seance;
use App\Entity\User;
use DateTimeImmutable;

class BuildNewReservation
{
    public function execute(User $user, Seance $seance, array $parameters): Reservation
    {
        $nombrePlaces = $parameters["nb_places"];
        $montantTotal = $seance -> getTarifNormal() * $nombrePlaces;
        $newReservation = new Reservation();
        $newReservation -> setReservePar($user);
        $newReservation -> setSeance($seance);
        $newReservation -> setNombrePlaces($nombrePlaces);
        $newReservation -> setMontantTotal($montantTotal);
        $newReservation -> setDateReservation(new DateTimeImmutable());
        return $newReservation;
    }
}