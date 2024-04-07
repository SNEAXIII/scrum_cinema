<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Services\UserServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\FormErrorNormalizer;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RegistrationController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/register', name: 'app_register')]
    public function register(
        Request             $request,
        HttpClientInterface $httpClient
    ): Response
    {
        $form = $this -> createForm(RegistrationFormType::class, []);
        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {
            $service = new UserServices($httpClient);
            $response = $service->postOneNewUser($form -> getData());
            dd($response);
//            $form -> addError();
//            new FormErrorNormalizer();


            // do anything else you need here, like send an email

            return $this -> redirectToRoute('app_films_index');
        }

        return $this -> render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
