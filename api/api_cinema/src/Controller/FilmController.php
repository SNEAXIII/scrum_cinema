<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
#[Route("/api/films")]
class FilmController extends AbstractController
{
    #[Route('', name: 'api_films_affiche_index', methods: ["GET"])]
    public function index(FilmRepository $filmRepository, SerializerInterface $serializer): Response
    {
        $header_json = ["content-type" => "application/json"];
        $group = ['groups' => 'list_film'];
        $arrayFilteredFilms = $filmRepository -> findAllFilmsAffiche();
        $jsonFilteredFilms = $serializer -> serialize($arrayFilteredFilms, "json", $group);
        return new Response($jsonFilteredFilms, Response::HTTP_OK, $header_json);
    }
    #[Route('/{id}', name: 'api_films_affiche_film', requirements: ['id' => '\d+'], methods: ["GET"])]
    public function show(FilmRepository $filmRepository, SerializerInterface $serializer,int $id): Response
    {
        $header_json = ["content-type" => "application/json"];
        $group = ['groups' => 'show_film'];
        $arrayFilteredFilms = $filmRepository -> find($id);
        $jsonFilteredFilms = $serializer -> serialize($arrayFilteredFilms, "json", $group);
        return new Response($jsonFilteredFilms, Response::HTTP_OK, $header_json);
    }
}

