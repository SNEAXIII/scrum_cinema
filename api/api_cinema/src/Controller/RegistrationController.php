<?php

namespace App\Controller;

use App\Entity\User;
use App\Validator\NewUserValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ["POST"])]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface      $entityManager,
        SerializerInterface $serializer
    ): Response
    {
        $bodyRequest = $request -> getContent();
        $parameters = json_decode($bodyRequest, true);
        $userValidator = new NewUserValidator($parameters, $entityManager);
        $validation = $userValidator -> validate();
        if ($validation['isValid']) {
            $newUser = new User();
            $newUser -> setEmail($parameters["email"]);
            $hashedPassword = $userPasswordHasher -> hashPassword($newUser, $parameters["password"]);
            $newUser -> setPassword($hashedPassword);
            $newUser -> setRoles(['ROLE_USER']);
            $entityManager -> persist($newUser);
            $entityManager -> flush();
            $group = ['groups' => 'create_user'];
            $jsoncontent = $serializer -> serialize($newUser, "json", $group);
            return new Response($jsoncontent,Response::HTTP_CREATED, ["content-type" => "application/json"]);
        } else {
            $jsoncontent = json_encode($validation['message']);
            return new Response($jsoncontent,Response::HTTP_BAD_REQUEST, ["content-type" => "application/json"]);
        }
    }
}
