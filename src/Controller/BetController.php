<?php

namespace App\Controller;

use App\Entity\BetQualificationCountries;
use App\Entity\BetUser;
use App\Entity\Matches;
use App\Entity\QualificationCountries;
use App\Form\BetUserFormType;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            if((clone $matche->getDate())->add(new DateInterval("PT3H")) > (new DateTime())) {
                $list_matches_bet[$matche->getId()][] = [
                    "id" => $matche->getId(),
                    "groupe" => ($matche->getCountrie1() == null) ? "null" : $matche->getCountrie1()->getCategories(),
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
            } else {
                $list_matches_bet[$matche->getId()][] = [
                    "id" => $matche->getId(),
                    "groupe" => ($matche->getCountrie1() == null) ? "null" : $matche->getCountrie1()->getCategories(),
                    "countrie_1" => ($matche->getCountrie1() == null) ? "null" : $matche->getCountrie1()->getName(),
                    "countrie_1_flag" => ($matche->getCountrie1() == null) ? "null" : $matche->getCountrie1()->getIsoFlag(),
                    "countrie_2" => ($matche->getCountrie2() == null) ? "null" : $matche->getCountrie2()->getName(),
                    "countrie_2_flag" => ($matche->getCountrie2() == null) ? "null" : $matche->getCountrie2()->getIsoFlag(),
                    "date" => $matche->getDate(),
                    "date_2_hours" => (clone $matche->getDate())->add(new DateInterval("PT2H")),
                    "score_countrie_1" => $matche->getScoreCountrie1(),
                    "score_countrie_2" => $matche->getScoreCountrie2(),
                    "type_match" => 100,
                    "score_countrie_1_user" => ($actual_bet_user == null) ? "null" : $actual_bet_user->getScoreCountrie1(),
                    "score_countrie_2_user" => ($actual_bet_user == null) ? "null" : $actual_bet_user->getScoreCountrie2(),
                ];
            }
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
        if($matche === null || $matche->getCountrie1() == null || $matche->getCountrie2() == null) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', []);
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
                if(($matche->getDate()) > (new DateTime())) {
                    if ($form->isSubmitted() && $form->isValid()) {
                        $bet->setMatches($matche);
                        $bet->setScoreCountrie1($form->get('score_countrie_1')->getData());
                        $bet->setScoreCountrie2($form->get('score_countrie_2')->getData());
                        $bet->setUser($this->getUser());
                        $bet->setCalculate(false);
                        $entityManager->persist($bet);
                        $entityManager->flush();
                        return $this->redirectToRoute('app_bet', ['_fragment' => 'matche_number_' . $id]);
                    }
                } else {
                    return $this->render('bundles/TwigBundle/Exception/toolate.html.twig');
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
        if($matche === null || $matche->getCountrie1() == null || $matche->getCountrie2() == null) {;
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', []);
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
                if(($matche->getDate()) > (new DateTime())) {
                    if ($form->isSubmitted() && $form->isValid()) {
                        $bet_matche->setScoreCountrie1($form->get('score_countrie_1')->getData());
                        $bet_matche->setScoreCountrie2($form->get('score_countrie_2')->getData());
                        $entityManager->persist($bet_matche);
                        $entityManager->flush();
                        return $this->redirectToRoute('app_bet', ['_fragment' => 'matche_number_' . $id]);
                    }
                } else {
                    return $this->render('bundles/TwigBundle/Exception/toolate.html.twig');
                }
                return $this->render('pages/bet_form.html.twig', ['matche' => $matche_res, "bet_form" => $form->createView()]);
            }
        }
    }

    #[Route('/bet/see/{id}', name: 'app_see_bet')]
    public function betSee(ManagerRegistry $doctrine, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$this->getUser()->isValideRegister()) {
            return $this->redirectToRoute('app_profil');
        }
        $matche = $doctrine->getRepository(Matches::class)->findOneBy(['id' => $id]);
        if($matche === null || $matche->getCountrie1() == null || $matche->getCountrie2() == null) {;
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', []);
        } else {
            $res_bet = [];
            if ((new DateTime()) > ($matche->getDate())) {
                $getAllBet = $doctrine->getRepository(BetUser::class)->findBy(['matches' => $id]);
                $res_matche = [
                    "id" => $matche->getId(),
                    "date" => $matche->getDate(),
                    "date_2_hours" => (clone $matche->getDate())->add(new DateInterval("PT2H")),
                    "type_match" => $matche->getTypeMatch(),
                    "countrie_1" => $matche->getCountrie1()->getName(),
                    "countrie_1_flag" => $matche->getCountrie1()->getIsoFlag(),
                    "countrie_2" => $matche->getCountrie2()->getName(),
                    "countrie_2_flag" => $matche->getCountrie2()->getIsoFlag(),
                    "score_countrie_1" => $matche->getScoreCountrie1(),
                    "score_countrie_2" => $matche->getScoreCountrie2(),
                ];
                foreach ($getAllBet as $bet) {
                    $res_bet[] = [
                        "bet_score_countrie_1" => $bet->getScoreCountrie1(),
                        "bet_score_countrie_2" => $bet->getScoreCountrie2(),
                        "name" => $bet->getUser()->getName(),
                    ];
                }
            } else {
                return $this->render('bundles/TwigBundle/Exception/you_cant_see.html.twig');
            }
            return $this->render('pages/see_bet_other_match.html.twig', ['matche' => $res_matche, 'bet' => $res_bet]);
        }
    }

    #[Route('/bet/bonus', name: 'app_bet_bonus')]
    public function betBonus(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$this->getUser()->isValideRegister()) {
            return $this->redirectToRoute('app_profil');
        }

        $getAllQualificationCountries = $doctrine->getRepository(QualificationCountries::class)->findAll();
        $list_qualif_countries = [];
        foreach ($getAllQualificationCountries as $qualifCountries) {
            $actual_bet_user = $doctrine->getRepository(BetQualificationCountries::class)->findOneBy(["user" => $this->getUser()->getId(), "qualification_countries" => $qualifCountries->getId()]);

                $list_qualif_countries[$qualifCountries->getId()][] = [
                    "id" => $qualifCountries->getId(),
                    "first_countrie_res" => ($qualifCountries->getFirstCountryRes() == null) ? null : $qualifCountries->getFirstCountryRes()->getName(),
                    "first_countrie_res_flag" => ($qualifCountries->getFirstCountryRes() == null) ? null : $qualifCountries->getFirstCountryRes()->getIsoFlag(),
                    "second_countrie_res" => ($qualifCountries->getSecondCountryRes() == null) ? null : $qualifCountries->getSecondCountryRes()->getName(),
                    "second_countrie_res_flag" => ($qualifCountries->getSecondCountryRes() == null) ? null : $qualifCountries->getSecondCountryRes()->getIsoFlag(),
                    "countrie_1" => ($qualifCountries->getCountrie1Eighth() == null) ? null : $qualifCountries->getCountrie1Eighth()->getName(),
                    "countrie_1_flag" => ($qualifCountries->getCountrie1Eighth() == null) ? null : $qualifCountries->getCountrie1Eighth()->getIsoFlag(),
                    "countrie_2" => ($qualifCountries->getCountrie2Eighth() == null) ? null : $qualifCountries->getCountrie2Eighth()->getName(),
                    "countrie_2_flag" => ($qualifCountries->getCountrie2Eighth() == null) ? null : $qualifCountries->getCountrie2Eighth()->getIsoFlag(),
                    "countrie_3" => ($qualifCountries->getCountrie3Eighth() == null) ? null : $qualifCountries->getCountrie3Eighth()->getName(),
                    "countrie_3_flag" => ($qualifCountries->getCountrie3Eighth() == null) ? null : $qualifCountries->getCountrie3Eighth()->getIsoFlag(),
                    "countrie_4" => ($qualifCountries->getCountrie4Eighth() == null) ? null : $qualifCountries->getCountrie4Eighth()->getName(),
                    "countrie_4_flag" => ($qualifCountries->getCountrie4Eighth() == null) ? null : $qualifCountries->getCountrie4Eighth()->getIsoFlag(),
                    "date" => $qualifCountries->getDate(),
                    "date_4_hours" => (clone $qualifCountries->getDate())->add(new DateInterval("PT4H")),
                    "user_select_countrie_1" => ($actual_bet_user == null) ? null : $actual_bet_user->getCountrie1()->getName(),
                    "user_select_countrie_1_flag" => ($actual_bet_user == null) ? null : $actual_bet_user->getCountrie1()->getIsoFlag(),
                    "user_select_countrie_2" => ($actual_bet_user == null) ? null : $actual_bet_user->getCountrie2()->getName(),
                    "user_select_countrie_2_flag" => ($actual_bet_user == null) ? null : $actual_bet_user->getCountrie2()->getIsoFlag(),
                    "type_phase" => $qualifCountries->getTypePhase()
                ];
        }

        return $this->render('pages/bet_bonus.html.twig', ["qualifCountries" => $list_qualif_countries]);

    }

    #[Route('/bet/bonus/edit/{id}', name: 'app_edit_bet_bonus')]
    public function betEditBonus(ManagerRegistry $doctrine, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$this->getUser()->isValideRegister()) {
            return $this->redirectToRoute('app_profil');
        }
        $qualifCountrie = $doctrine->getRepository(QualificationCountries::class)->findOneBy(['id' => $id]);
        if($qualifCountrie === null || $qualifCountrie->getCountrie1Eighth() == null || $qualifCountrie->getCountrie2Eighth() == null || $qualifCountrie->getCountrie3Eighth() == null || $qualifCountrie->getCountrie4Eighth() == null) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', []);
        } else {
            $bet_qualif_countrie = $doctrine->getRepository(BetQualificationCountries::class)->findOneBy(["user" => $this->getUser()->getId(), "qualification_countries" => $qualifCountrie->getId()]);
            if($bet_qualif_countrie === null) {
                return $this->redirectToRoute('app_add_bet_bonus', ['id' => $id]);
            } else {
                if(($qualifCountrie->getDate()) > (new DateTime())) {
                    $form = $this->createFormBuilder($bet_qualif_countrie)
                        ->add('countrie_1',ChoiceType::class, [
                            'choices'  => [
                                $qualifCountrie->getCountrie1Eighth()->getName() => $qualifCountrie->getCountrie1Eighth(),
                                $qualifCountrie->getCountrie2Eighth()->getName() => $qualifCountrie->getCountrie2Eighth(),
                            ],
                        ])->add('countrie_2',ChoiceType::class, [
                            'choices'  => [
                                $qualifCountrie->getCountrie3Eighth()->getName() => $qualifCountrie->getCountrie3Eighth(),
                                $qualifCountrie->getCountrie4Eighth()->getName() => $qualifCountrie->getCountrie4Eighth(),
                            ],
                        ])->getForm();
                    $form->handleRequest($request);
                    if ($form->isSubmitted() && $form->isValid()) {
                        $bet_qualif_countrie->setCountrie1($form->get('countrie_1')->getData());
                        $bet_qualif_countrie->setCountrie2($form->get('countrie_2')->getData());
                        $entityManager->persist($bet_qualif_countrie);
                        $entityManager->flush();
                        return $this->redirectToRoute('app_bet_bonus');
                    }
                } else {
                    return $this->render('bundles/TwigBundle/Exception/toolate.html.twig');
                }
                return $this->render('pages/bet_form_bonus.html.twig', ["bet_form" => $form->createView()]);
            }
        }
    }

    #[Route('/bet/bonus/add/{id}', name: 'app_add_bet_bonus')]
    public function betAddBonus(ManagerRegistry $doctrine, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$this->getUser()->isValideRegister()) {
            return $this->redirectToRoute('app_profil');
        }
        $qualifCountrie = $doctrine->getRepository(QualificationCountries::class)->findOneBy(['id' => $id]);
        if($qualifCountrie === null || $qualifCountrie->getCountrie1Eighth() == null || $qualifCountrie->getCountrie2Eighth() == null || $qualifCountrie->getCountrie3Eighth() == null || $qualifCountrie->getCountrie4Eighth() == null) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', []);
        } else {
            $bet_qualif_countrie = $doctrine->getRepository(BetQualificationCountries::class)->findOneBy(["user" => $this->getUser()->getId(), "qualification_countries" => $qualifCountrie->getId()]);
            if($bet_qualif_countrie != null) {
                return $this->redirectToRoute('app_edit_bet_bonus', ['id' => $id]);
            } else {
                if(($qualifCountrie->getDate()) > (new DateTime())) {
                    $new_bet = new BetQualificationCountries();
                    $form = $this->createFormBuilder($new_bet)
                        ->add('countrie_1',ChoiceType::class, [
                            'choices'  => [
                                $qualifCountrie->getCountrie1Eighth()->getName() => $qualifCountrie->getCountrie1Eighth(),
                                $qualifCountrie->getCountrie2Eighth()->getName() => $qualifCountrie->getCountrie2Eighth(),
                            ],
                        ])->add('countrie_2',ChoiceType::class, [
                            'choices'  => [
                                $qualifCountrie->getCountrie3Eighth()->getName() => $qualifCountrie->getCountrie3Eighth(),
                                $qualifCountrie->getCountrie4Eighth()->getName() => $qualifCountrie->getCountrie4Eighth(),
                            ],
                        ])->getForm();
                    $form->handleRequest($request);
                    if ($form->isSubmitted() && $form->isValid()) {
                        $new_bet->setCountrie1($form->get('countrie_1')->getData());
                        $new_bet->setCountrie2($form->get('countrie_2')->getData());
                        $new_bet->setQualificationCountries($qualifCountrie);
                        $new_bet->setUser($this->getUser());
                        $new_bet->setCalculation(false);
                        $entityManager->persist($new_bet);
                        $entityManager->flush();
                        return $this->redirectToRoute('app_bet_bonus');
                    }
                } else {
                    return $this->render('bundles/TwigBundle/Exception/toolate.html.twig');
                }
                return $this->render('pages/bet_form_bonus.html.twig', ["bet_form" => $form->createView()]);
            }
        }
    }

    #[Route('/bet/bonus/see/{id}', name: 'app_see_bet_bonus')]
    public function betBonusSee(ManagerRegistry $doctrine, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$this->getUser()->isValideRegister()) {
            return $this->redirectToRoute('app_profil');
        }
        $qualifCountrie = $doctrine->getRepository(QualificationCountries::class)->findOneBy(['id' => $id]);
        if($qualifCountrie === null || $qualifCountrie->getCountrie1Eighth() == null || $qualifCountrie->getCountrie2Eighth() == null || $qualifCountrie->getCountrie3Eighth() == null || $qualifCountrie->getCountrie4Eighth() == null) {;
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', []);
        } else {
            $res_bet = [];
            if ((new DateTime()) > ($qualifCountrie ->getDate())) {
                $getAllBet = $doctrine->getRepository(BetQualificationCountries::class)->findBy(['qualification_countries' => $id]);
                $res_matche = [
                    "id" => $qualifCountrie->getId(),
                    "date" => $qualifCountrie->getDate(),
                    "date_2_hours" => (clone $qualifCountrie->getDate())->add(new DateInterval("PT4H")),
                ];
                foreach ($getAllBet as $bet) {
                    $res_bet[] = [
                        "bet_countrie_1" => $bet->getCountrie1()->getName(),
                        "bet_countrie_1_flag" => $bet->getCountrie1()->getIsoFlag(),
                        "bet_countrie_2" => $bet->getCountrie2()->getName(),
                        "bet_countrie_2_flag" => $bet->getCountrie2()->getIsoFlag(),
                        "name" => $bet->getUser()->getName(),
                    ];
                }
                return $this->render('pages/see_bet_bonus_other.html.twig', ['matche' => $res_matche, 'bet' => $res_bet]);
            } else {
                return $this->render('bundles/TwigBundle/Exception/you_cant_see.html.twig');
            }
        }
    }
}
