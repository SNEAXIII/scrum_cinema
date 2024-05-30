<?php

namespace App\Validator;

use App\Entity\Seance;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PhpParser\Node\Param;

class NewReservationValidator
{


    public function validate(User $user,Seance $seance, array $parameters): array
    {
//        if ($this -> jwtTokenManager -> decode($this -> token)) {
//            $userRepository = $this -> entityManager -> getRepository(User::class);
//
//            // Password error check
//            if (!empty($message["passwordErrors"])) {
//                return [
//                    "isValid" => false,
//                    "message" => $message
//                ];
//            }
        return [
            "isValid" => true,
            "message" => ["Ok"]
        ];
    }
}