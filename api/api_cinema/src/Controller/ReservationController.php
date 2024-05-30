<?php

namespace App\Controller;

use App\Repository\SeanceRepository;
use App\Services\BuildNewReservation;
use App\Services\BuildNewUser;
use App\Validator\NewReservationValidator;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\UserRepository;

class ReservationController extends AbstractController
{
    private JWTTokenManagerInterface $jwtManager;
    private TokenStorageInterface $tokenStorageInterface;
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private SeanceRepository $seanceRepository;

    /**
     * @param JWTTokenManagerInterface $jwtManager
     * @param TokenStorageInterface $tokenStorageInterface
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param SeanceRepository $seanceRepository
     */
    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        TokenStorageInterface    $tokenStorageInterface,
        EntityManagerInterface   $entityManager,
        UserRepository           $userRepository,
        SeanceRepository         $seanceRepository
    )
    {
        $this -> jwtManager = $jwtManager;
        $this -> tokenStorageInterface = $tokenStorageInterface;
        $this -> entityManager = $entityManager;
        $this -> userRepository = $userRepository;
        $this -> seanceRepository = $seanceRepository;
    }


    #[Route('/api/reserver', name: 'app_reserver', methods: ["POST"])]
    public function register(
        Request             $request,
        SerializerInterface $serializer,
    ): Response
    {
        $token = $this -> tokenStorageInterface -> getToken();
        $payload = $this -> jwtManager -> decode($token);
        $email = $payload["username"];
        $userObject = $this -> userRepository -> findOneBy(['email' => $email]);
        $bodyRequest = $request -> getContent();
        $parameters = json_decode($bodyRequest, true);
//        dd($userObject);
        $seanceObject = $this->seanceRepository->find($parameters["id"]);
        $reservationValidator = new NewReservationValidator();
        $validation = $reservationValidator -> validate($userObject, $seanceObject ,$parameters);
        if ($validation['isValid']) {
            $buildNewReservation = new BuildNewReservation();
            $newReservation = $buildNewReservation -> execute($userObject,$seanceObject ,$parameters);
            $this -> entityManager -> persist($newReservation);
            $this -> entityManager -> flush();
            $jsoncontent = $serializer -> serialize($newReservation, "json", ['groups' => 'create_reservation']);
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
