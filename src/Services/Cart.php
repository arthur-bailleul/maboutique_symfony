<?php
namespace App\Services;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart {
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function add($id) {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (empty($cart[$id])) {
            $cart[$id]=1;
        } else {
            $cart[$id]=$cart[$id]+1;
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function decrease($id) {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if ($cart[$id] == 1) {
            unset($cart[$id]);
        } else {
            $cart[$id]=$cart[$id]-1;
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function get() {
        return $this->requestStack->getSession()->get('cart', []);
    }

    public function remove($id) {
        $cart = $this->requestStack->getSession()->get('cart', []);

        unset($cart[$id]);

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function clear() {
        return $this->requestStack->getSession()->remove('cart');
    }
}


?>
