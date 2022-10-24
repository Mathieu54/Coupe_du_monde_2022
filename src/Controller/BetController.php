<?php

namespace App\Controller;

use App\Entity\BetUser;
use App\Entity\Matches;
use DateInterval;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BetController extends AbstractController
{
    #[Route('/bet', name: 'app_bet')]
    public function index(ManagerRegistry $doctrine): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $matches_type_7 = $doctrine->getRepository(Matches::class)->findAll();
        $list_matches_bet = [];
        foreach ($matches_type_7 as $matche) {
            $actual_bet_user = $doctrine->getRepository(BetUser::class)->findOneBy(["user" => $this->getUser()->getId(), "matches" => $matche->getId()]);
            $list_matches_bet[$matche->getId()][] = [
                "countrie_1" => ($matche->getCountrie1() == null) ? "null" : $matche->getCountrie1()->getName(),
                "countrie_1_flag" => ($matche->getCountrie1() == null) ? "null" : $matche->getCountrie1()->getIsoFlag(),
                "countrie_2" => ($matche->getCountrie2() == null) ? "null" : $matche->getCountrie2()->getName(),
                "countrie_2_flag" => ($matche->getCountrie2() == null) ? "null" : $matche->getCountrie2()->getIsoFlag(),
                "date" => $matche->getDate(),
                "date_2_hours" => (clone $matche->getDate())->add(new DateInterval("PT2H")),
                "score_countrie_1" => $matche->getScoreCountrie1(),
                "score_countrie_2" => $matche->getScoreCountrie2(),
                "type_match" => $matche->getTypeMatch(),
                "score_countrie_1_user" => ($actual_bet_user == null) ? "null" : $actual_bet_user->getScoreCountrie1(),
                "score_countrie_2_user" => ($actual_bet_user == null) ? "null" : $actual_bet_user->getScoreCountrie2(),
            ];
        }
        return $this->render('pages/bet.html.twig', [
            'matches' => $list_matches_bet,
        ]);
    }

    #[Route('/bet/phase/1', name: 'app_bet_phase_1')]
    public function betPhase1(ManagerRegistry $doctrine, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        //TODO CHANGE HERE THE FORMAT
        return $this->render('pages/bet_phase_1.html.twig', []);
    }
}
