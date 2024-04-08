<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
            $service = new UserService($httpClient);
            $response = $service -> postOneNewUser($form -> getData());
            $statusCode = $response -> getStatusCode();
            $arrayContent = json_decode($response -> getContent(false), true);

            if ($statusCode === 201) {
                $this -> addFlash("succes","Votre compte à été créé avec ");
                return $this -> redirectToRoute('app_films_index');
            } else {
                $emailErrors = $arrayContent["emailErrors"];
                foreach ($emailErrors as $emailError) {
                    $form["email"] -> addError(new FormError($emailError));
                }
                $passwordErrors = $arrayContent["passwordErrors"];
                foreach ($passwordErrors as $passwordError) {
                    $form["plainPassword"] -> addError(new FormError($passwordError));
                }
            }
        }
        return $this -> render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
