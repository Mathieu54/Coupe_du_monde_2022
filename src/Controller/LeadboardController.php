<?php

namespace App\Controller;

use App\Entity\User;
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
        $user_list = [];
        foreach ($all_user_scores as $key => $user) {
            $user_list[] = [
                "leadboard_number" => $key + 1,
                "name" => $user->getUser()->getName(),
                "scores" => $user->getScores(),
                "bet_win" => $user->getBetWin(),
                "bet_win_bonus" => $user->getBetWinBonus(),
                "bet_loses" => $user->getBetLose(),
            ];
        }
        $user_list_groups = null;
        if ($this->getUser()->getGroupes() != null) {
            $get_user_groups = $doctrine->getRepository(User::class)->findBy(['groupes' => $this->getUser()->getGroupes()]);
            foreach ($get_user_groups as $key => $user) {
                $user->getUserScores();
                $user_list_groups[] = [
                    "leadboard_number" => $key + 1,
                    "name" => $user->getName(),
                    "scores" => $user->getUserScores()->getScores(),
                    "bet_win" => $user->getUserScores()->getBetWin(),
                    "bet_win_bonus" => $user->getUserScores()->getBetWinBonus(),
                    "bet_loses" => $user->getUserScores()->getBetLose(),
                ];
            }
        }
        return $this->render('pages/leadboard.html.twig', [
            'leadboard' => $user_list,
            'leadboard_groups' => $user_list_groups
        ]);
    }
}
