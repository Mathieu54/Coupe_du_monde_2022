<?php

namespace App\Command;

use App\Entity\BetUser;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
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
        foreach ($getBet as $bet) {
            // create a log channel
            $log = new Logger('name');
            $log->pushHandler(new StreamHandler('var/log/calculation_scores.log', Level::Warning));

            $log->warning('Foo');
            $log->error('Bar');
            if (($getDateNow->getTimestamp() >= $bet->getMatches()->getDate()->getTimestamp()) && ($bet->getMatches()->getScoreCountrie1() !== null) && ($bet->getMatches()->getScoreCountrie2() !== null)) {
                $scores_final = $bet->getUser()->getUserScores()->getScores();
                $bet_win_final = $bet->getUser()->getUserScores()->getBetWin();
                $bet_win_bonus_final = $bet->getUser()->getUserScores()->getBetWinBonus();
                $bet_lose_final = $bet->getUser()->getUserScores()->getBetLose();
                if (($bet->getScoreCountrie1() == $bet->getMatches()->getScoreCountrie1()) && ($bet->getScoreCountrie2() == $bet->getMatches()->getScoreCountrie2())) {
                    $scores_final += 2;
                    $bet_win_bonus_final++;
                }
                if (($bet->getMatches()->getScoreCountrie1() == $bet->getMatches()->getScoreCountrie2()) && ($bet->getScoreCountrie1() == $bet->getScoreCountrie2())) {
                    $scores_final++;
                    $bet_win_final++;
                } else if (($bet->getMatches()->getScoreCountrie1() > $bet->getMatches()->getScoreCountrie2()) && ($bet->getScoreCountrie1() > $bet->getScoreCountrie2())) {
                    $scores_final++;
                    $bet_win_final++;
                } else if (($bet->getMatches()->getScoreCountrie1() < $bet->getMatches()->getScoreCountrie2()) && ($bet->getScoreCountrie1() < $bet->getScoreCountrie2())) {
                    $scores_final++;
                    $bet_win_final++;
                } else {
                    $bet_lose_final++;
                }
                $bet->getUser()->getUserScores()->setBetLose($bet_lose_final);
                $bet->getUser()->getUserScores()->setBetWinBonus($bet_win_bonus_final);
                $bet->getUser()->getUserScores()->setBetWin($bet_win_final);
                $bet->getUser()->getUserScores()->setScores($scores_final);
                $bet->setCalculate(true);
                $entityManager->persist($bet);
                $entityManager->flush();
            }
        }
        return Command::SUCCESS;
    }
}