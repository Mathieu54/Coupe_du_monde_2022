<?php

namespace App\Command;

use App\Entity\BetUser;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:calculation_scores')]
class CalculationScores extends Command
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityManager = $this->doctrine->getManager();
        $getBet = $this->doctrine->getRepository(BetUser::class)->findBy(['calculate' => '0']);
        $getDateNow = new DateTime();
        $dateFormat = "d/m/Y G:i:s";
        $output = "[%datetime%][%level_name%]: %message%\n";
        $formatter = new LineFormatter($output, $dateFormat);
        $stream = new StreamHandler('var/log/calculation_scores.log', Level::Debug);
        $stream->setFormatter($formatter);
        $log = new Logger('security');
        $log->pushHandler($stream);
        $log->info("=============================================");
        $log->info("=============START CALCULATION===============");
        $log->info("=============================================");
        foreach ($getBet as $bet) {
            if (($getDateNow->getTimestamp() >= $bet->getMatches()->getDate()->getTimestamp()) && ($bet->getMatches()->getScoreCountrie1() !== null) && ($bet->getMatches()->getScoreCountrie2() !== null)) {
                $log->info('Vérification Pari N°' . $bet->getId() . ' est calculé => ' . $bet->isCalculate());
                $log->info($bet->getUser()->getName() . ' a parié => ' . $bet->getMatches()->getCountrie1()->getName() . ' ' . $bet->getScoreCountrie1() . ' - ' . $bet->getScoreCountrie2() . ' ' . $bet->getMatches()->getCountrie2()->getName());
                $scores_final = $bet->getUser()->getUserScores()->getScores();
                $bet_win_final = $bet->getUser()->getUserScores()->getBetWin();
                $bet_win_bonus_final = $bet->getUser()->getUserScores()->getBetWinBonus();
                $bet_lose_final = $bet->getUser()->getUserScores()->getBetLose();
                if (($bet->getScoreCountrie1() == $bet->getMatches()->getScoreCountrie1()) && ($bet->getScoreCountrie2() == $bet->getMatches()->getScoreCountrie2())) {
                    $scores_final += 2;
                    $bet_win_bonus_final++;
                    $log->info('+2 points car scores exactes');
                }
                if (($bet->getMatches()->getScoreCountrie1() == $bet->getMatches()->getScoreCountrie2()) && ($bet->getScoreCountrie1() == $bet->getScoreCountrie2())) {
                    $scores_final++;
                    $bet_win_final++;
                    $log->info('+1 point égalité');
                } else if (($bet->getMatches()->getScoreCountrie1() > $bet->getMatches()->getScoreCountrie2()) && ($bet->getScoreCountrie1() > $bet->getScoreCountrie2())) {
                    $scores_final++;
                    $bet_win_final++;
                    $log->info('+1 point équipe gagnante');
                } else if (($bet->getMatches()->getScoreCountrie1() < $bet->getMatches()->getScoreCountrie2()) && ($bet->getScoreCountrie1() < $bet->getScoreCountrie2())) {
                    $scores_final++;
                    $bet_win_final++;
                    $log->info('+1 point équipe gagnante');
                } else {
                    $bet_lose_final++;
                    $log->info('0 point');
                }
                $bet->getUser()->getUserScores()->setBetLose($bet_lose_final);
                $bet->getUser()->getUserScores()->setBetWinBonus($bet_win_bonus_final);
                $bet->getUser()->getUserScores()->setBetWin($bet_win_final);
                $bet->getUser()->getUserScores()->setScores($scores_final);
                $bet->setCalculate(true);
                $log->info('Pari N°' . $bet->getId() . ' est calculé => ' . $bet->isCalculate() . ' TERMINE !');
                $log->info('=============================================================');
                $entityManager->persist($bet);
                $entityManager->flush();
            }
        }
        $log->info("===========================================");
        $log->info("=============END CALCULATION===============");
        $log->info("===========================================");
        return Command::SUCCESS;
    }
}