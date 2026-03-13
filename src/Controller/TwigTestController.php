<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TwigTestController extends AbstractController
{
    #[Route('/twig/test', name: 'app_twig_test')]
    public function index(): Response
    {
        $balise = '<strong>salut</strong>';
        $tab = ['nom' => 'bailleul', 'prenom' => 'arthur', 'age' => '23'];
        return $this->render('twig_test/index.html.twig', [
            'controller_name' => 'TwigTestController',
            'infos' => $tab,
            'strong' => $balise,
        ]);
    }
}