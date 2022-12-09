<?php

namespace App\Command;

use App\Entity\BetQualificationCountries;
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
        $getBetBonus = $this->doctrine->getRepository(BetQualificationCountries::class)->findBy(['calculation' => '0']);
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
        foreach ($getBetBonus as $betBonus) {
            if (($getDateNow->getTimestamp() >= $betBonus->getQualificationCountries()->getDate()->getTimestamp()) && ($betBonus->getQualificationCountries()->getFirstCountryRes() !== null) && ($betBonus->getQualificationCountries()->getSecondCountryRes() !== null)) {
                $log->info('Vérification Pari N°' . $betBonus->getId() . ' est calculé => ' . $betBonus->isCalculation());
                $log->info($betBonus->getUser()->getName() . ' a parié => ' . $betBonus->getCountrie1()->getName() . ' et ' . $betBonus->getCountrie2()->getName());
                $scores_final = $betBonus->getUser()->getUserScores()->getScores();
                $bet_win_final = $betBonus->getUser()->getUserScores()->getBetWin();
                $bet_win_bonus_final = $betBonus->getUser()->getUserScores()->getBetWinBonus();
                $bet_lose_final = $betBonus->getUser()->getUserScores()->getBetLose();
                if (($betBonus->getCountrie1()->getName() == $betBonus->getQualificationCountries()->getFirstCountryRes()->getName()) && ($betBonus->getCountrie2()->getName() == $betBonus->getQualificationCountries()->getSecondCountryRes()->getName())) {
                    $scores_final += 5;
                    $bet_win_bonus_final++;
                    $bet_win_final++;
                    $log->info('+5 points car scores exactes');
                } else {
                    if (($betBonus->getCountrie1()->getName() == $betBonus->getQualificationCountries()->getFirstCountryRes()->getName())) {
                        $scores_final += 2;
                        $bet_win_final++;
                        $log->info('+2 points 1 pays trouvé');
                    } else if (($betBonus->getCountrie2()->getName() == $betBonus->getQualificationCountries()->getSecondCountryRes()->getName())) {
                        $scores_final += 2;
                        $bet_win_final++;
                        $log->info('+2 points 1 pays trouvé');
                    } else {
                        $bet_lose_final++;
                        $log->info('0 point');
                    }
                }
                $betBonus->getUser()->getUserScores()->setBetLose($bet_lose_final);
                $betBonus->getUser()->getUserScores()->setBetWinBonus($bet_win_bonus_final);
                $betBonus->getUser()->getUserScores()->setBetWin($bet_win_final);
                $betBonus->getUser()->getUserScores()->setScores($scores_final);
                $betBonus->setCalculation(true);
                $log->info('Pari N°' . $betBonus->getId() . ' est calculé => ' . $betBonus->isCalculation() . ' TERMINE !');
                $log->info('=============================================================');
                $entityManager->persist($betBonus);
                $entityManager->flush();
            }
        }
        $log->info("===========================================");
        $log->info("=============END CALCULATION===============");
        $log->info("===========================================");
        return Command::SUCCESS;
    }
}