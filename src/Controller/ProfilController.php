<?php

namespace App\Controller;

use App\Entity\BetQualificationCountries;
use App\Entity\BetUser;
use App\Entity\Matches;
use App\Entity\QualificationCountries;
use App\Entity\User;
use App\Entity\UserScores;
use App\Form\ProfilEditFormType;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $get_scores = $doctrine->getRepository(UserScores::class)->findOneBy(["user" => $this->getUser()->getId()]);
        $all_matche = $doctrine->getRepository(BetUser::class)->findBy(["user" => $this->getUser()->getId()]);
        $get_position_leadboard = $doctrine->getRepository(UserScores::class)->findPositionLeadboardUser($this->getUser()->getId());
        $matches_all = $doctrine->getRepository(Matches::class)->findBy([], ['id' => 'DESC']);
        $list_matches_bet = [];
        foreach ($matches_all as $matche) {
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
        $qualification = $doctrine->getRepository(QualificationCountries::class)->findBy([], ['id' => 'DESC']);
        $list_test = [];
        foreach ($qualification as $list_qualif) {
            $actual_bet_user = $doctrine->getRepository(BetQualificationCountries::class)->findOneBy(["user" => $this->getUser()->getId(), "qualification_countries" => $list_qualif->getId()]);
            $list_test[$list_qualif->getId()][] = [
                "id" => $list_qualif->getId(),
                "date" => $list_qualif->getDate(),
                "date_2_hours" => (clone $list_qualif->getDate())->add(new DateInterval("PT2H")),
                "bet_bonus_flag_1_user" => ($actual_bet_user == null) ? "null" : $actual_bet_user->getCountrie1()->getIsoFlag(),
                "bet_bonus_flag_2_user" => ($actual_bet_user == null) ? "null" : $actual_bet_user->getCountrie2()->getIsoFlag(),
                "bet_bonus_flag_1_res" => ($list_qualif->getFirstCountryRes() == null) ? "null" : $list_qualif->getFirstCountryRes()->getIsoFlag(),
                "bet_bonus_flag_2_res" => ($list_qualif->getSecondCountryRes() == null) ? "null" : $list_qualif->getSecondCountryRes()->getIsoFlag(),
                "bet_bonus_flag_1" => ($list_qualif->getCountrie1Eighth() == null) ? "null" : $list_qualif->getCountrie1Eighth()->getIsoFlag(),
                "bet_bonus_flag_2" => ($list_qualif->getCountrie2Eighth() == null) ? "null" : $list_qualif->getCountrie2Eighth()->getIsoFlag(),
                "bet_bonus_flag_3" => ($list_qualif->getCountrie3Eighth() == null) ? "null" : $list_qualif->getCountrie3Eighth()->getIsoFlag(),
                "bet_bonus_flag_4" => ($list_qualif->getCountrie4Eighth() == null) ? "null" : $list_qualif->getCountrie4Eighth()->getIsoFlag(),
                "type_phase" => $list_qualif->getTypePhase()
            ];
        }
        return $this->render('pages/profil.html.twig', [
            'matches' => $list_matches_bet,
            'number_bet_matche' => count($all_matche),
            'scores' => $get_scores->getScores(),
            'bet_win' => $get_scores->getBetWin(),
            'bet_loses' => $get_scores->getBetLose(),
            'bet_win_bonus' => $get_scores->getBetWinBonus(),
            'position' => $get_position_leadboard,
            'qualification_list' => $list_test
        ]);
    }

    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function profil_edit(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $confirmationNotif = null;
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(ProfilEditFormType::class);
        $form->handleRequest($request);
        $get_user = $doctrine->getRepository(User::class)->findOneBy(["id" => $this->getUser()->getId()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $get_user->setMessage($form->get('punchline_message')->getData());
            $get_user->setStatusScoreEmail($form->get('status_score_email')->getData());
            $get_user->setReminderBetEmail($form->get('reminder_bet_email')->getData());
            try {
                $entityManager->persist($get_user);
                $entityManager->flush();
                $confirmationNotif = "success";
            } catch (\Exception $e) {
                $confirmationNotif = "error";
            }
        }
        return $this->render('pages/profil-edit.html.twig', [
            'profilEditForm' => $form->createView(),
            'confirmationNotif' => $confirmationNotif,
        ]);
    }

    #[Route('/profil/{id}', name: 'app_profil_other')]
    public function other_profil(int $id, ManagerRegistry $doctrine): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->getUser()->getId() === $id) {
            return $this->redirectToRoute('app_profil');
        }
        $get_scores = $doctrine->getRepository(UserScores::class)->findOneBy(["user" => $id]);
        if($get_scores != null) {
            $all_matche = $doctrine->getRepository(BetUser::class)->findBy(["user" => $id]);
            $user_info = $doctrine->getRepository(User::class)->findOneBy(["id" => $id]);
            $get_position_leadboard = $doctrine->getRepository(UserScores::class)->findPositionLeadboardUser($id);
            $matches_all = $doctrine->getRepository(Matches::class)->findBy([], ['id' => 'DESC']);
            $list_matches_bet = [];
            foreach ($matches_all as $matche) {
                $actual_bet_user = $doctrine->getRepository(BetUser::class)->findOneBy(["user" => $id, "matches" => $matche->getId()]);
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
            return $this->render('pages/profil-other.html.twig', [
                'matches' => $list_matches_bet,
                'name' => $user_info->getName(),
                'groupes' => ($user_info->getGroupes() == null) ? "null" : $user_info->getGroupes()->getName(),
                'valide_register' => $user_info->isValideRegister(),
                'url_image' => $user_info->getUrlPicture(),
                'number_bet_matche' => count($all_matche),
                'scores' => $get_scores->getScores(),
                'bet_win' => $get_scores->getBetWin(),
                'bet_loses' => $get_scores->getBetLose(),
                'bet_win_bonus' => $get_scores->getBetWinBonus(),
                'position' => $get_position_leadboard
            ]);
        } else {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas !');
        }
    }
}
