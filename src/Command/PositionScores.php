<?php

namespace App\Command;

use App\Entity\BetUser;
use App\Entity\User;
use App\Entity\UserScores;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

#[AsCommand(name: 'app:position_scores')]
class PositionScores extends Command
{
    private $doctrine;
    private MailerInterface $mailerInterface;

    public function __construct(ManagerRegistry $doctrine, MailerInterface $mailerInterface)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
        $this->mailerInterface = $mailerInterface;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $all_user_scores = $this->doctrine->getRepository(UserScores::class)->findBy([],['scores' => 'DESC']);
        $user_list = [];
        $scores_save = 0;
        $leadboard = 0;
        foreach ($all_user_scores as $user_res) {
            if($user_res->getScores() != $scores_save) {
                $scores_save = $user_res->getScores();
                $leadboard++;
            }
            $user_list[] = [
                "leadboard_number" => $leadboard,
                "id" => $user_res->getUser()->getId(),
                "name" => $user_res->getUser()->getName(),
                "scores" => $user_res->getScores()
            ];
        }
        $getAllUser = $this->doctrine->getRepository(User::class)->findAll();
        foreach ($getAllUser as $user) {
            $position = $this->doctrine->getRepository(UserScores::class)->findPositionLeadboardUser($user->getId());
            $email = (new TemplatedEmail())
                ->from(new Address($_ENV["MAIL_BOT"], $_ENV["MAIL_BOT_NAME"]))
                ->to($user->getEmail())
                ->subject('Récapitulatif des scores coupe du monde 2022 !')
                ->htmlTemplate('mail/position_scores.html.twig')->context([
                    'name' => $user->getName(),
                    'position' => $position,
                    'scores' => $user->getUserScores()->getScores(),
                    'bet_win' => $user->getUserScores()->getBetWin(),
                    'bet_loses' => $user->getUserScores()->getBetLose(),
                    'bet_win_bonus' => $user->getUserScores()->getBetWinBonus(),
                    'leadboard' => $user_list,
                ]);
            try {
                $this->mailerInterface->send($email);
            } catch (TransportExceptionInterface $e) {
                dump($e);
            }
        }
        return Command::SUCCESS;
    }
}