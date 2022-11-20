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
        $entityManager = $this->doctrine->getManager();
        $getAllUser = $this->doctrine->getRepository(User::class)->findOneBy(["id" => 9]);
            dump($getAllUser);
            $email = (new TemplatedEmail())
            ->from(new Address($_ENV["MAIL_BOT"], $_ENV["MAIL_BOT_NAME"]))
            ->to($getAllUser->getEmail())
            ->subject('Début Paris Coupe du Monde 2022')
            ->htmlTemplate('mail/start_bet.html.twig');
            try {
                $this->mailerInterface->send($email);
            } catch (TransportExceptionInterface $e) {
                dump($e);
            }
        return Command::SUCCESS;
    }
}