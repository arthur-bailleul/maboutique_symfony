<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/compte', name: 'account')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user->isActive()) {
            $request->getSession()->invalidate();
            $this->addFlash('danger', "Votre compte n'est pas encore activé. Vérifiez vos mails.");
            return $this->redirectToRoute('login');
        }

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
}
