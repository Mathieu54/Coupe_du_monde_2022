<?php

namespace App\Controller;

use App\Entity\UserScores;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeadboardController extends AbstractController
{
    #[Route('/leadboard', name: 'app_leadboard')]
    public function index(ManagerRegistry $doctrine): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$this->getUser()->isValideRegister()) {
            return $this->redirectToRoute('app_profil');
        }
        $all_user_scores = $doctrine->getRepository(UserScores::class)->findBy([],['scores' => 'DESC']);

        //TODO HERE REWORK LEADBOARD !!
        foreach ($all_user_scores as $user) {

        }
        if ($this->getUser()->getGroupes() != null) {

        }
        return $this->render('pages/leadboard.html.twig', [
            'leadboard' => [1],
        ]);
    }
}
