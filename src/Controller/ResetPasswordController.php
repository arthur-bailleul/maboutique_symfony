<?php

namespace App\Controller;

use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    private $manager;
    private $resetPasswordRequestRepository;


    public function __construct(EntityManagerInterface $manager, ResetPasswordRequestRepository $resetPasswordRequestRepository)
    {
        $this->manager = $manager;
        $this->resetPasswordRequestRepository = $resetPasswordRequestRepository;
    }


    #[Route('', name: 'forgot_password_request')]
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            return $this->processSendingPasswordResetEmail($email, $mailer);
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form
        ]);
    }


    #[Route('/check-email', name: 'check_email')]
    public function checkEmail(): Response
    {
        return $this->render('reset_password/check_email.html.twig');
    }


    #[Route('/reset/{token}', name: 'reset_password')]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, string $token): Response
    {
        $resetRequest = $this->resetPasswordRequestRepository->findByToken($token);

        if (!$resetRequest) {
            $this->addFlash('reset_password_error', 'Ce lien de réinitialisation est invalide.');
            return $this->redirectToRoute('forgot_password_request');
        }

        if ($resetRequest->isExpired()) {
            $this->manager->remove($resetRequest);
            $this->manager->flush();

            $this->addFlash('reset_password_error', 'Ce lien a expiré (valide 30 minutes). Veuillez faire une nouvelle demande.');
            return $this->redirectToRoute('forgot_password_request');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user = $resetRequest->getUser();

            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));

            $this->manager->remove($resetRequest);
            $this->manager->flush();

            $this->addFlash('success', 'Votre mot de passe a été mis à jour avec succès !');
            return $this->redirectToRoute('login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form
        ]);
    }


    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->manager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData
        ]);

        if (!$user) {
            return $this->redirectToRoute('check_email');
        }

        $this->resetPasswordRequestRepository->removeAllForUser($user);

        $token = uniqid();
        $expiresAt = new \DateTime('+30 minutes');

        $resetRequest = new ResetPasswordRequest($user, $token, $expiresAt);
        $this->manager->persist($resetRequest);
        $this->manager->flush();

        $resetUrl = $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from('no-reply@maboutique.com')
            ->to($user->getEmail())
            ->subject('Réinitialisation de votre mot de passe')
            ->html($this->renderView('reset_password/email.html.twig', [
                'resetUrl' => $resetUrl,
                'firstName' => $user->getFirstName()
            ]));

        $mailer->send($email);

        return $this->redirectToRoute('check_email');
    }
}
