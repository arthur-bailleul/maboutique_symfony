<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RegisterController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $manager;


    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->manager= $manager;
    }


    #[Route('/register', name: 'register')]
    public function register(Request $request, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $user->getPassword();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $user->setIsActive(false);

            $activationToken = uniqid();
            $user->setActivationToken($activationToken);

            $this->manager->persist($user);
            $this->manager->flush();

            $activationUrl = $this->generateUrl(
                'account_activate',
                ['token' => $activationToken],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $email = (new Email())
                ->from('no-reply@maboutique.com')
                ->to($user->getEmail())
                ->subject('Activez votre compte MaBoutique')
                ->html($this->renderView('register/activation_email.html.twig', [
                    'activationUrl' => $activationUrl,
                    'firstName' => $user->getFirstName()
                ]));

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre compte a été créé ! Veuillez consulter votre boîte mail pour activer votre compte.'
            );

            return $this->redirectToRoute('login');
        }

        return $this->render('register/index.html.twig', [
            // 'controller_name' => 'RegisterController',
            'form' => $form
        ]);
    }


    #[Route('/activate/{token}', name: 'account_activate')]
    public function activate(string $token, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['activationToken' => $token]);

        if (!$user) {
            $this->addFlash('danger', "Ce lien d'activation est invalide ou a déjà été utilisé.");
            return $this->redirectToRoute('login');
        }

        $user->setIsActive(true);
        $user->setActivationToken(null);
        $this->manager->flush();

        $this->addFlash('success', 'Votre compte est maintenant actif ! Vous pouvez vous connecter.');

        return $this->redirectToRoute('login');
    }
}
