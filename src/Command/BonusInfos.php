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

#[AsCommand(name: 'app:bonusinfos')]
class BonusInfos extends Command
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
        $getAllUser = $this->doctrine->getRepository(User::class)->findAll();
        foreach ($getAllUser as $user) {
            $email = (new TemplatedEmail())
                ->from(new Address($_ENV["MAIL_BOT"], $_ENV["MAIL_BOT_NAME"]))
                ->to($user->getEmail())
                ->subject('Quoi ? Des paris bonus sont arrivés ?')
                ->htmlTemplate('mail/bonus_infos.html.twig')->context([
                    'name' => $user->getName(),
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