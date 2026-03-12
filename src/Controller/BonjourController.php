<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// final = pas surchargeable
final class BonjourController extends AbstractController
{
    // '/test' et id de la route
    #[Route('/bonjour', name: 'app_bonjour')]
    public function nomPasUtile(): Response
    // le nom de la fonction n'a pas d'importance
    {
        return $this->render('bonjour/index.html.twig', [
            'controller_name' => 'BonjourController',
        ]);
    }
}