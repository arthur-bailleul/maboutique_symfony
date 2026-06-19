<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\SearchFilter;
use App\Form\SearchFilterType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    // #[Route('/nos-produits/{id}/{slug}', name: 'products')]
    // public function index(EntityManagerInterface $entityManager, int $id, string $slug): Response
    // public function index(EntityManagerInterface $entityManager): Response
    #[Route('/nos-produits', name: 'products')]
    // #[Route('/nos-produits/{id}', name: 'product')]
    // public function index(ProductRepository $repo, ?int $id = null, Request $request): Response
    public function index(ProductRepository $repo, Request $request): Response
    {
        // dd($id, $slug);
        // $repo = $entityManager->getRepository(Product::class);

        // $product16 = $repo->find(16);
        // $products = $repo->findAll();
        // $productName = $repo->findOneByName('aspernatur non enim');
        $productNames = $repo->findByName('aspernatur non enim');
        // $productCat = $repo->findBy(['category' => '157']);
        $prices = $repo->findBy([], ['price' => 'asc']);

        $search = new SearchFilter();
        $form = $this->createForm(SearchFilterType::class, $search);
        $form->handleRequest($request);

        $products = [];
        $notProduct = "";


        if ($form->isSubmitted() && $form->isValid()) {
            // dd($search->getCategories());

            $products = $repo->findBy([
                'category' => $search->getCategories()
            ]);


            if (!$products) {
                // $products = $repo->findAll();
                $notProduct = "il n'y a pas de produit dans ces categories.";
            }

            // foreach ($search->getCategories() as $category) {
                // dd($category);
                // dump($category);
                // $products[] = $repo->findBy(['category' => $category]);


                            // $categories[] = $category;
                // $products[] = $repo->findBy(['id' => $categories]);
                // dd($products);
            // }
            // $products = array_merge(...$products);

            // dd($products);

        } else {
            // dd("uwu");
            $products = $repo->findAll();
        }

        // dump($product16);
        // dump($products);
        // dump($productName);
        // dd($productCat);
        // dd($prices);
        // dd($productNames);

        // if ($id) {
        //     $product = $repo->find($id);
        //     return $this->render('product/details.html.twig', [
        //         'product' => $product
        //     ]);
        // }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form,
            'notproduct' => $notProduct
        ]);
    }

    // #[Route('/produit/{slug}', name: 'product')]
    // public function show(ProductRepository $products, ?string $slug): Response
    // {
    //     $product = $products->findOneBySlug($slug);
    //     return $this->render('product/show.html.twig', [
    //         'product' => $product
    //     ]);
    // }
    #[Route('/produit/{slug}', name: 'product')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
