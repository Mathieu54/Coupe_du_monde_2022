<?php

namespace App\Controller;

use App\Entity\BetUser;
use App\Entity\UserScores;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(ManagerRegistry $doctrine): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $all_matche = $doctrine->getRepository(BetUser::class)->findBy(["user" => $this->getUser()->getId()]);
        $get_scores = $doctrine->getRepository(UserScores::class)->findOneBy(["user" => $this->getUser()->getId()]);
        $get_position_leadboard = $doctrine->getRepository(UserScores::class)->findPositionLeadboardUser($this->getUser()->getId());
        return $this->render('pages/profil.html.twig', [
            'number_bet_matche' => count($all_matche),
            'scores' => $get_scores->getScores(),
            'bet_win' => $get_scores->getBetWin(),
            'bet_loses' => $get_scores->getBetLose(),
            'bet_win_bonus' => $get_scores->getBetWinBonus(),
            'position' => $get_position_leadboard
        ]);
    }
}
