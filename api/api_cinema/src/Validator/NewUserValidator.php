<?php

namespace App\Validator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NewUserValidator
{
    private string $email;
    private string $password;
    private EntityManagerInterface $entityManager;

    /**
     * @param array $parameters
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(array $parameters, EntityManagerInterface $entityManager)
    {
        $this -> email = $parameters["email"];
        $this -> password = $parameters["password"];
        $this -> entityManager = $entityManager;
    }

    public function validate(): array
    {
        $emailErrors = [];
        $passwordErrors = [];
        $regex_validation = [
            "/^.{10,}$/" => "Le mot de passe doit contenir au moins 10 caractères",
            "/^[a-zA-Z\d?;!:=]*$/" => "Le mot de passe doit contenir uniquement des lettres minuscules ou majuscules, des chiffres, ou les symboles suivants : ?;!:=",
            "/^(?=.*\d).*$/" => "Le mot de passe doit contenir au moins chiffre",
            "/^(?=.*[?;!:=]).*$/" => "Le mot de passe doit contenir au moins un caractère spécial : ?;!:=",
            "/^(?=.*[a-z]).*$/" => "Le mot de passe doit contenir au moins une minuscule",
            "/^(?=.*[A-Z]).*$/" => "Le mot de passe doit contenir au moins une majuscule",
        ];

        // Email validation
        $userRepository = $this -> entityManager -> getRepository(User::class);

        if ($userRepository -> findOneBy(["email" => $this -> email])) {
            $emailErrors[] = "L'email saisi existe déjà, veuillez en choisir une autre !!!";
        }

        if (!filter_var($this -> email, FILTER_VALIDATE_EMAIL)) {
            $emailErrors[] = "L'email saisi est invalide, veuillez en écrire une avec le bon format !!!";
        }

        // Password validation
        foreach ($regex_validation as $pattern => $errorMessage) {
            if (!preg_match($pattern, $this -> password)) {
                $passwordErrors[] = $errorMessage;
            }
        }

        if (empty($emailErrors) && empty($passwordErrors)) {
            $isValid = true;
            $message = ["Ok"];
        } else {
            $isValid = false;
            $message = [
                "emailErrors" => $emailErrors,
                "passwordError" => $passwordErrors
            ];
        }
        return [
            "isValid" => $isValid,
            "message" => $message
        ];
    }
}