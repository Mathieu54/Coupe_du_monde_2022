<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

#[AsCommand(name: 'app:testemail2')]
class TestEmail2 extends Command
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
        return Command::SUCCESS;
    }
}