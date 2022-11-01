<?php

namespace App\Command;

use App\Entity\BetUser;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(name: 'app:calculation_scores')]
class CalculationScores extends Command
{
    private $doctrine;
    private $mailerInterface;

    public function __construct(ManagerRegistry $doctrine, MailerInterface $mailerInterface)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
        $this->mailerInterface = $mailerInterface;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityManager = $this->doctrine->getManager();
        $getBet = $this->doctrine->getRepository(BetUser::class)->findBy(['calculate' => '0']);
        $getDateNow = new DateTime();
        foreach ($getBet as $bet) {
            if (($getDateNow->getTimestamp() >= $bet->getMatches()->getDate()->getTimestamp()) && ($bet->getMatches()->getScoreCountrie1() != null) && ($bet->getMatches()->getScoreCountrie2() != null)) {
                //THIS PART IS NOT DEFINED NEED MORE REFLEXION
                //WORKING IN PROGRESS !!!!!!!! //TODO
                if (($bet->getScoreCountrie1() == $bet->getMatches()->getScoreCountrie1()) && ($bet->getScoreCountrie2() == $bet->getMatches()->getScoreCountrie2())) {
                    dump("BONUS 2 POINTS");
                }
                if (($bet->getMatches()->getScoreCountrie1() == $bet->getMatches()->getScoreCountrie2()) && ($bet->getScoreCountrie1() == $bet->getScoreCountrie2())) {

                } else if (($bet->getMatches()->getScoreCountrie1() > $bet->getMatches()->getScoreCountrie2()) && ($bet->getScoreCountrie1() > $bet->getScoreCountrie2())) {

                } else if (($bet->getMatches()->getScoreCountrie1() < $bet->getMatches()->getScoreCountrie2()) && ($bet->getScoreCountrie1() < $bet->getScoreCountrie2())) {

                } else {
                    dump("perdu");
                }
                $bet->setCalculate(true);
                $entityManager->persist($bet);
                $entityManager->flush();
            }
        }
        return Command::SUCCESS;
    }
}