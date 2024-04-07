<?php

namespace App\Controller;

use App\Services\Constants;
use App\Services\FilmServices;
use App\Services\FilmUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FilmController extends AbstractController
{
    #[Route('', name: 'app_accueil')]
    #[Route('/films', name: 'app_films_index')]
    public function index(FilmServices $filmService): Response
    {
        $films = $filmService -> getAllFilms();
        return $this -> render(
            "film/index.html.twig",
            ["films" => $films, "link" => "http://localhost:8001/films/"]);
    }

    #[Route('/films/{id}', name: 'app_films_details', requirements: ['id' => '\d+'], methods: ["GET"])]
    public function show(FilmServices $filmService, FilmUtils $utils, int $id): Response
    {
        $film = $filmService -> getOneFilms($id);
        $film = $utils -> convertDateAndIntToStringForAFilm($film);
        return $this -> render(
            "film/details.html.twig",
            ["film" => $film]);

    }
}
