<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestloginController extends AbstractController
{
    #[Route('/api/testlogin', name: 'app_testlogin')]
    public function index(): Response
    {
        return $this->render('testlogin/index.html.twig', [
            'controller_name' => 'TestloginController',
        ]);
    }
}
