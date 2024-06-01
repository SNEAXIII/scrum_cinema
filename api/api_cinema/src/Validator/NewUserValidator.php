<?php

namespace App\Validator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NewUserValidator
{
    private string $email;
    private string $plainPassword;
    private string $confirmPassword;
    private EntityManagerInterface $entityManager;

    /**
     * @param array $parameters
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(array $parameters, EntityManagerInterface $entityManager)
    {
        $this -> email = $parameters["email"];
        $this -> plainPassword = $parameters["plainPassword"];
        $this -> confirmPassword = $parameters["confirmPassword"];
        $this -> entityManager = $entityManager;
    }

    public function validate(): array
    {
        $message = [
            "emailErrors" => "",
            "passwordErrors" => ""
        ];
        $regex_validation = [
            "/.{10,}/" => "10 caractères",
            "/(?=.*\d).*/" => "un chiffre",
            "/(?=.*[?;!:=]).*/" => "un caractère spécial ?;!:=",
            "/(?=.*[a-z]).*/" => "une minuscule",
            "/(?=.*[A-Z]).*/" => "une majuscule",
        ];

        // Email validation
        $userRepository = $this -> entityManager -> getRepository(User::class);

        if ($userRepository -> findOneBy(["email" => $this -> email])) {
            $message["emailErrors"] =
                "L'email saisi appartient à un compte existant, veuillez en choisir une autre !!!";
        } elseif (!filter_var($this -> email, FILTER_VALIDATE_EMAIL)) {
            $message["emailErrors"] =
                "L'email saisi est invalide, veuillez en écrire une avec le bon format !!!";
        }
        // Email error check
        if (!empty($message["emailErrors"])) {
            return [
                "isValid" => false,
                "message" => $message
            ];
        }
        // Password validation
        if ($this -> plainPassword !== $this -> confirmPassword) {
            $message["passwordErrors"] = "Les mots de passe ne sont pas identiques.";
        } elseif (!preg_match("/^[a-zA-Z\d?;!:=]*$/", $this -> plainPassword)) {
            $message["passwordErrors"] =
                "Le mot de passe doit contenir uniquement des lettres minuscules ou majuscules," .
            " des chiffres, ou les symboles suivants : ?;!:=.";
        } else {
            $arrayPasswordErrors = [];
            foreach ($regex_validation as $pattern => $errorMessage) {
                if (!preg_match($pattern, $this -> plainPassword)) {
                    $arrayPasswordErrors[] = "$errorMessage";
                }
            }
            if (!empty($arrayPasswordErrors)) {
                $messageErrors = implode(", ", $arrayPasswordErrors);
                $message["passwordErrors"] = "Le mot de passe doit contenir au moins : $messageErrors!";
            }
        }

        // Password error check
        if (!empty($message["passwordErrors"])) {
            return [
                "isValid" => false,
                "message" => $message
            ];
        }
        return [
            "isValid" => true,
            "message" => ["Ok"]
        ];
    }
}