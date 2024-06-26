<?php

namespace App\Controller;

use App\Services\FilmServices;
use App\Services\FilmUtils;
use App\Services\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

    #[Route('/films/{id}', name: 'app_films_details', requirements: ['id' => '\d+'], methods: ["GET", "POST"])]
    public function show(
        FilmServices        $filmService,
        FilmUtils           $utils,
        Request             $request,
        int                 $id,
        HttpClientInterface $httpClient,
    ): Response
    {
        $error = null;
        $token = $request -> getSession() -> get('token');
        $isLogged = !is_null($token);
        if ($isLogged && $request -> getMethod() == "POST") {
            $idSeance = $request -> get("id_seance");
            $nombrePlaces = intval($request -> get("nb_places"));
            if (!empty($idSeance) && !empty($nombrePlaces) && $nombrePlaces > 0) {
                $reservationService = new ReservationService($httpClient);
                $response = $reservationService -> postReserverUneSeance($token, $idSeance, $nombrePlaces);
                $arrayContent = json_decode($response -> getContent(false), true);
                if ($response -> getStatusCode() === 201) {
                    $titleFilm = $arrayContent["seance"]["film"]["titre"];
                    $nameSalle = $arrayContent["seance"]["salle"]["nom"];
                    $this -> addFlash(
                        "success",
                        "Vous avez réservé $nombrePlaces places pour le film $titleFilm dans la salle $nameSalle avec succès."
                    );
                    return $this -> redirectToRoute('app_films_index');
                } else {
                    $this -> addFlash(
                        "error",
                        $arrayContent["message"]
                    );
                }
            }
        }

        $film = $filmService -> getOneFilms($id);
        $film = $utils -> formatAndSortFilmSeances($film);
        return $this -> render(
            "film/details.html.twig",
            [
                "film" => $film,
                "estConnecte" => $isLogged,
                "error" => $error,
            ]);

    }
}
