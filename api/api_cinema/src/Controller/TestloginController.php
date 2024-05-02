<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestloginController extends AbstractController
{
    #[Route('/api/logintest', name: 'app_testlogin')]
    public function index(Security $security): Response
    {
        dd($security->getUser());
        return $this->render('testlogin/index.html.twig', [
            'controller_name' => 'TestloginController',
        ]);
    }
}
