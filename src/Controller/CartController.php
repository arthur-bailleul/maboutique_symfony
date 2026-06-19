<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;

use App\Services\Cart;

final class CartController extends AbstractController
{
    #[Route('/panier', name: 'cart')]
    public function index(Cart $cart, ProductRepository $repo): Response
    {

        $products = [];
        $total = 0;
        $quantities = 0;

        $cart = $cart->get();

        // id -> qty
        foreach ($cart as $id => $qty) {
            $product = $repo->findOneById($id);

            if (!$product) continue;

            $products[$id] = $product;
            $total += $product->getPrice()*$qty;
            $quantities += $qty;
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'products' => $products,
            'total' => $total,
            'quantite' => $quantities

        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_cart')]
    public function add($id, Cart $cart): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/decrease/{id}', name: 'decrease_cart')]
    public function decrease($id, Cart $cart): Response
    {
        $cart->decrease($id);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove/{id}', name: 'remove_cart')]
    public function remove($id, Cart $cart): Response
    {
        $cart->remove($id);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/clear', name: 'clear_cart')]
    public function clear(Cart $cart): Response
    {
        $cart->clear();
        return $this->redirectToRoute('cart');
    }


}
