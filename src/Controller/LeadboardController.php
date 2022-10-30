<?php

namespace App\Controller;

use App\Entity\BetUser;
use App\Entity\User;
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
        $all_user = $doctrine->getRepository(User::class)->findAll();
        $leadboard_final = [];
        foreach ($all_user as $user) {
            $all_bet_user = $doctrine->getRepository(BetUser::class)->findBy(["user" => $user->getId()]);
            $score = 0;
            foreach ($all_bet_user as $bet) {
                if (($bet->getScoreCountrie1() === $bet->getMatches()->getScoreCountrie1()) && ($bet->getScoreCountrie2() === $bet->getMatches()->getScoreCountrie2())) {
                    $score += 10;
                }
                if (($bet->getScoreCountrie1() === $bet->getScoreCountrie2()) && ($bet->getMatches()->getScoreCountrie1() === $bet->getMatches()->getScoreCountrie2())) {
                    $score += 5;
                }
                if (($bet->getScoreCountrie1() > $bet->getScoreCountrie2()) && ($bet->getMatches()->getScoreCountrie1() > $bet->getMatches()->getScoreCountrie2())) {
                    $score += 5;
                }
                if (($bet->getScoreCountrie1() < $bet->getScoreCountrie2()) && ($bet->getMatches()->getScoreCountrie1() < $bet->getMatches()->getScoreCountrie2())) {
                    $score += 5;
                }
            }
            $leadboard_final[] = [
                "name" => $user->getName(),
                "score" => $score,
            ];
        }
        return $this->render('pages/leadboard.html.twig', [
            'leadboard' => $leadboard_final,
        ]);
    }
}
