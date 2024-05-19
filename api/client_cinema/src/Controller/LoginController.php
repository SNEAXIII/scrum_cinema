<?php

namespace App\Controller;

use App\Form\LoginFormType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LoginController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(
        Request             $request,
        HttpClientInterface $httpClient
    ): Response
    {
        $form = $this -> createForm(LoginFormType::class, []);
        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {
            $service = new UserService($httpClient);
            $response = $service -> postLoginCheck($form -> getData());
            if ($response -> getStatusCode() === 200) {
                $this -> addFlash("succes", "Vous vous êtes authentifié avec ");
                $arrayContent = json_decode($response -> getContent(false), true);
                $request -> getSession() -> set('token', $arrayContent["token"]);
                return $this -> redirectToRoute('app_films_index');
            } else {
                $form["username"] -> addError(new FormError("Le compte n'existe pas ou le mot de passe est incorrect"));
            }
        }
        return $this -> render('login/login.html.twig', [
            'loginForm' => $form,
        ]);
    }
}
