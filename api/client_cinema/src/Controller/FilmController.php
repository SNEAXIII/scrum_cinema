<?php

namespace App\Controller;

use App\Services\FilmServices;
use App\Services\FilmUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FilmController extends AbstractController
{
    #[Route('', name: 'app_accueil')]
    #[Route('/films', name: 'app_films_index')]
    public function index(FilmServices $filmService, FilmUtils $utils): Response
    {
        $films = $filmService -> getAllFilms();
        $films = $utils -> convertFilmsIntToMinute($films);
        return $this -> render(
            "film/index.html.twig",
            ["films" => $films, "link" => "http://localhost:8001/films/"]);
    }

    #[Route('/films/{id}', name: 'app_films_details', requirements: ['id' => '\d+'], methods: ["GET"])]
    public function show(
        FilmServices $filmService,
        FilmUtils    $utils,
        Request      $request,
        int $id,
    ): Response
    {
        $estConnecte = !is_null($request -> getSession() -> get('token'));
        $film = $filmService -> getOneFilms($id);
        $film = $utils -> formatAndSortFilmSeances($film);
        return $this -> render(
            "film/details.html.twig",
            [
                "film" => $film,
                "estConnecte" => $estConnecte
            ]);

    }
}
