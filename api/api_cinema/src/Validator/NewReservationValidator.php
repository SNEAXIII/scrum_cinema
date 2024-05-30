<?php

namespace App\Validator;

use App\Entity\Seance;
use App\Entity\User;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PhpParser\Node\Param;

class NewReservationValidator
{


    public function validate(
        User                  $user,
        Seance                $seance,
        array                 $parameters,
        ReservationRepository $reservationRepository
    ): array
    {
        if (!isset($parameters["nb_places"])) {
            return [
                "isValid" => false,
                "message" => "Une erreur s'est produite, veuillez contacter l'administrateur du site !"
            ];
        }
        if (!$parameters["nb_places"]) {
            return [
                "isValid" => false,
                "message" => "Vous devez saisir un nombre de places !"
            ];
        }
        $actualReservationNumber = $reservationRepository -> getSumOrderedPlaceForOneSeance(77);
        $maxReservationNumber = $seance -> getSalle() -> getNombrePlaces();
        $isEnoughSpace = $parameters["nb_places"] + $actualReservationNumber <= $maxReservationNumber;
        if (!$isEnoughSpace) {
            $remainingPlaces = $maxReservationNumber - $actualReservationNumber;
            return [
                "isValid" => false,
                "message" => "Le nombre de places demandé est trop élevé, il reste $remainingPlaces places!"
            ];
        }
        return [
            "isValid" => true,
            "message" => "Ok"
        ];
    }
}