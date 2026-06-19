<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Services\Cart;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Cart $cart): Response
    {
        // $session = $requestStack->getSession();
        // $session->getBag()
        // $session->set('cart', []);
        // $panier = $session->get('cart', []);


        // dump($panier);
        // dd($panier);
        // $cart->add(1);
        $cart->clear();
        // dd($cart->get());


        return $this->render('home/index.html.twig', [
            // 'controller_name' => 'HomeController',
            'cart' => $cart->get()
        ]);
    }
}
