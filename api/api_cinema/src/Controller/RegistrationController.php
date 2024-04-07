<?php

namespace App\Controller;

use App\Services\BuildNewUser;
use App\Validator\NewUserValidator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_register', methods: ["POST"])]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface      $entityManager,
        SerializerInterface         $serializer,
        LoggerInterface             $logger
    ): Response
    {
        $bodyRequest = $request -> getContent();
        $logger -> debug($bodyRequest);
        $parameters = json_decode($bodyRequest, true);
        $logger -> debug(implode(", ", $parameters));
        $userValidator = new NewUserValidator($parameters, $entityManager);
        $validation = $userValidator -> validate();
        if ($validation['isValid']) {
            $buildNewUser = new BuildNewUser($userPasswordHasher);
            $newUser = $buildNewUser -> execute($parameters);
            $entityManager -> persist($newUser);
            $entityManager -> flush();
            $jsoncontent = $serializer -> serialize($newUser, "json", ['groups' => 'create_user']);
            return new Response(
                $jsoncontent,
                Response::HTTP_CREATED,
                ["content-type" => "application/json"]
            );
        } else {
            $jsoncontent = json_encode($validation['message']);
            return new Response(
                $jsoncontent,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ["content-type" => "application/json"]
            );
        }
    }
}
