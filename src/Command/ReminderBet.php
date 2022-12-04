<?php

namespace App\Command;

use App\Entity\BetUser;
use App\Entity\Matches;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

#[AsCommand(name: 'app:reminder_bet')]
class ReminderBet extends Command
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
        $getAllUser = $this->doctrine->getRepository(User::class)->findBy(["reminder_bet_email" => true]);
        $getNextMatch = $this->doctrine->getRepository(Matches::class)->findNextMatche();
        $dateFormat = "d/m/Y G:i:s";
        $output = "[%datetime%][%level_name%]: %message%\n";
        $formatter = new LineFormatter($output, $dateFormat);
        $stream = new StreamHandler('var/log/reminder_bet.log', Level::Debug);
        $stream->setFormatter($formatter);
        $log = new Logger('security');
        $log->pushHandler($stream);
        $log->info("=============================================");
        $log->info("================START REMINDER===============");
        $log->info("=============================================");
        foreach ($getAllUser as $user) {
            $searchBet = $this->doctrine->getRepository(BetUser::class)->findOneBy(["user" => $user->getId(), "matches" => $getNextMatch->getId()]);
            if($searchBet == null) {
                $log->info("L'utilisateur " . $user->getName() . " n'a pas parié sur le match id : " . $getNextMatch->getId() . " " . $getNextMatch->getCountrie1()->getName() . "-" . $getNextMatch->getCountrie2()->getName());
                $email = (new TemplatedEmail())
                    ->from(new Address($_ENV["MAIL_BOT"], $_ENV["MAIL_BOT_NAME"]))
                    ->to($user->getEmail())
                    ->subject('Rappel paris sur le prochain matche !')
                    ->htmlTemplate('mail/reminder_bet.html.twig')->context([
                        'name' => $user->getName(),
                        'countrie_1' => $getNextMatch->getCountrie1()->getName(),
                        'countrie_2' => $getNextMatch->getCountrie2()->getName(),
                    ]);
                try {
                    $this->mailerInterface->send($email);
                } catch (TransportExceptionInterface $e) {
                    $log->error("Erreur d'envoi email : " . $e);
                }
            }
        }
        $log->info("===========================================");
        $log->info("===============END REMINDER================");
        $log->info("===========================================");
        return Command::SUCCESS;
    }
}