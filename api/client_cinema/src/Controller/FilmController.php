<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\FilmServices;

class FilmController extends AbstractController
{
    #[Route('', name: 'app_app_accueil')]
    #[Route('/films', name: 'app_posts_index')]
    public function index(FilmServices $filmService): Response
    {
        $films = $filmService->getAllFilms();
        return $this->render("film/index.html.twig",
            ["films"=>$films]);
    }
}
