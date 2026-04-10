<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    // #[Route('/nos-produits/{id}/{slug}', name: 'products')]
    // public function index(EntityManagerInterface $entityManager, int $id, string $slug): Response
    // public function index(EntityManagerInterface $entityManager): Response
    #[Route('/nos-produits', name: 'products')]
    #[Route('/nos-produits/{id}', name: 'product')]
    public function index(ProductRepository $repo, ?int $id = null): Response
    {
        // dd($id, $slug);
        // $repo = $entityManager->getRepository(Product::class);

        // $product16 = $repo->find(16);
        // $products = $repo->findAll();
        // $productName = $repo->findOneByName('aspernatur non enim');
        $productNames = $repo->findByName('aspernatur non enim');
        // $productCat = $repo->findBy(['category' => '157']);
        $prices = $repo->findBy([], ['price' => 'asc']);

        // dump($product16);
        // dump($products);
        // dump($productName);
        // dd($productCat);
        // dd($prices);
        // dd($productNames);

        if ($id) {
                $product = $repo->find($id);
                return $this->render('product/details.html.twig', [
                    'product' => $product
                ]);
            }

            return $this->render('product/index.html.twig', [
                'products' => $repo->findAll()
            ]);
        }
}
