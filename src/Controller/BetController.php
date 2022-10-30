<?php

namespace App\Controller;

use App\Entity\BetUser;
use App\Entity\Matches;
use App\Form\BetUserFormType;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
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
        if (!$this->getUser()->isValideRegister()) {
            return $this->redirectToRoute('app_profil');
        }
        $matches_type_7 = $doctrine->getRepository(Matches::class)->findAll();
        $list_matches_bet = [];
        foreach ($matches_type_7 as $matche) {
            $actual_bet_user = $doctrine->getRepository(BetUser::class)->findOneBy(["user" => $this->getUser()->getId(), "matches" => $matche->getId()]);
            $list_matches_bet[$matche->getId()][] = [
                "id" => $matche->getId(),
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

    #[Route('/bet/add/{id}', name: 'app_add_bet')]
    public function betAdd(ManagerRegistry $doctrine, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$this->getUser()->isValideRegister()) {
            return $this->redirectToRoute('app_profil');
        }
        $matche = $doctrine->getRepository(Matches::class)->findOneBy(['id' => $id]);
        if($matche === null || $matche->getCountrie1() == null || $matche->getCountrie1() == null) {
            return $this->render('bundles/TwigBundle/error404.html.twig', []);
        } else {
            $bet_matche = $doctrine->getRepository(BetUser::class)->findOneBy(["user" => $this->getUser()->getId(), "matches" => $matche->getId()]);
            if($bet_matche != null) {
                return $this->redirectToRoute('app_edit_bet', ['id' => $id]);
            } else {
                $bet = new BetUser();
                $matche_res = [
                    "type_match" => $matche->getTypeMatch(),
                    "countrie_1" => $matche->getCountrie1()->getName(),
                    "countrie_1_flag" => $matche->getCountrie1()->getIsoFlag(),
                    "countrie_2" => $matche->getCountrie2()->getName(),
                    "countrie_2_flag" => $matche->getCountrie2()->getIsoFlag(),
                ];
                $form = $this->createForm(BetUserFormType::class);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $bet->setMatches($matche);
                    $bet->setScoreCountrie1($form->get('score_countrie_1')->getData());
                    $bet->setScoreCountrie2($form->get('score_countrie_2')->getData());
                    $bet->setUser($this->getUser());
                    $entityManager->persist($bet);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_bet', ['_fragment' => 'matche_number_' . $id]);
                }
                return $this->render('pages/bet_form.html.twig', ['matche' => $matche_res, "bet_form" => $form->createView()]);
            }
        }
    }

    #[Route('/bet/edit/{id}', name: 'app_edit_bet')]
    public function betEdit(ManagerRegistry $doctrine, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$this->getUser()->isValideRegister()) {
            return $this->redirectToRoute('app_profil');
        }
        $matche = $doctrine->getRepository(Matches::class)->findOneBy(['id' => $id]);
        if($matche === null || $matche->getCountrie1() == null || $matche->getCountrie1() == null) {;
            return $this->render('bundles/TwigBundle/error404.html.twig', []);
        } else {
            $bet_matche = $doctrine->getRepository(BetUser::class)->findOneBy(["user" => $this->getUser()->getId(), "matches" => $matche->getId()]);
            if($bet_matche === null) {
                return $this->redirectToRoute('app_add_bet', ['id' => $id]);
            } else {
                $matche_res = [
                    "type_match" => $matche->getTypeMatch(),
                    "countrie_1" => $matche->getCountrie1()->getName(),
                    "countrie_1_flag" => $matche->getCountrie1()->getIsoFlag(),
                    "countrie_2" => $matche->getCountrie2()->getName(),
                    "countrie_2_flag" => $matche->getCountrie2()->getIsoFlag(),
                ];
                $form = $this->createForm(BetUserFormType::class);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $bet_matche->setScoreCountrie1($form->get('score_countrie_1')->getData());
                    $bet_matche->setScoreCountrie2($form->get('score_countrie_2')->getData());
                    $entityManager->persist($bet_matche);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_bet', ['_fragment' => 'matche_number_' . $id] );
                }
                return $this->render('pages/bet_form.html.twig', ['matche' => $matche_res, "bet_form" => $form->createView()]);
            }
        }
    }
}
