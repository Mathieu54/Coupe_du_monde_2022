<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BetController extends AbstractController
{
    #[Route('/bet', name: 'app_bet')]
    public function index(): Response
    {
        return $this->render('pages/bet.html.twig', [
            'controller_name' => 'BetController',
        ]);
    }
}
