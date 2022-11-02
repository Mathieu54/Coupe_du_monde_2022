<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserScores;
use App\Form\RegistrationFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailerInterface): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($_ENV["REGISTER_ACTIVE"] == "false") {
                return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
            }
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setGroupes(null);
            $user->setPaid(false);
            $user->setValideRegister(false);
            $user->setUrlPicture("https://xsgames.co/randomusers/assets/avatars/pixel/" . rand(0,53) . ".jpg");
            $user->setCreatedAt(new Datetime());
            $user->setModifiedAt(new Datetime());
            $user_score = new UserScores();
            $user_score->setUser($user);
            $user_score->setScores(0);
            $entityManager->persist($user);
            $entityManager->persist($user_score);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from(new Address($_ENV["MAIL_BOT"], $_ENV["MAIL_BOT_NAME"]))
                ->to($user->getEmail())
                ->subject('Inscription Parie Coupe du Monde 2022')
                ->htmlTemplate('mail/inscription.html.twig')
            ;

            $mailerInterface->send($email);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('pages/register.html.twig', [
            'registrationForm' => $form->createView(),
            'registerActive' => $_ENV["REGISTER_ACTIVE"]
        ]);
    }
}
