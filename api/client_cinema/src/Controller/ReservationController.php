<?php

namespace App\Controller;

use App\Services\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ReservationController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/reserver/{id}',
        name: 'app_reserver',
        requirements: ['id' => '\d+'],
        methods: ["GET"])
    ]
    public function index(Request             $request,
                          HttpClientInterface $httpClient,
                          int                 $id
    ): Response
    {
        $token = $request->getSession()->get('token');
        $reservationService = new ReservationService($httpClient);
        $reservationService->postReserverUnFilm($token,$id);
        return $this -> render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
}
