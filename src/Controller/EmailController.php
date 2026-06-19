<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class EmailController extends AbstractController
{
    #[Route('/email', name: 'email')]
    public function index(MailerInterface $mailer): Response
    {
        $urlDeReinitialisation = 'https://localhost:8000.com/reset-password/un-token-securise';

        $email = (new Email())
            ->from('no-reply@maboutique.com')
            ->to('arthur.bailleul0@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Réinitialisation de votre mot de passe')
            ->text('Sending emails is fun again!')
            ->html("
                <p>Bonjour,</p>
                <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
                <p><a href='" . $urlDeReinitialisation . "'>Cliquez ici pour modifier votre mot de passe</a></p>
            ");
        $mailer->send($email);

        return $this->redirectToRoute('login');
    }
}
