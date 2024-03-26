<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FilmController extends AbstractController
{
    #[Route('/films/list', name: 'api_films_affiche_index', methods: ["GET"])]
    public function index(FilmRepository $filmRepository, SerializerInterface $serializer): Response
    {
        $header_json = ["content-type" => "application/json"];
        $group = ['groups' => 'list_film'];
        $arrayFilteredFilms = $filmRepository -> findAllFilmsAffiche();
        $jsonFilteredFilms = $serializer -> serialize($arrayFilteredFilms, "json", $group);
        return new Response($jsonFilteredFilms, Response::HTTP_OK, $header_json);
    }
}

