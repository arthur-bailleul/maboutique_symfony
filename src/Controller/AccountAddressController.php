<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AddressRepository;

final class AccountAddressController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager= $manager;
    }

    #[Route('/compte/adresses', name: 'account_address')]
    public function index(): Response
    {
        return $this->render('account/addresses.html.twig', [
            'addresses' => [],
        ]);
    }


    #[Route('/compte/adresses/add', name: 'account_address_add')]
    public function add(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $this->manager->persist($address);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien ete cree!'
            );

            return $this->redirectToRoute('account_address');
        }
        return $this->render('account/add_addresses.html.twig', [
            'form'=> $form
        ]);
    }

    #[Route('/compte/modifier-adresses/{id}', name: 'account_address_edit')]
    public function edit(Request $request, Address $address): Response
    // injection de dependence addresse
    {
        if ($this->getUser() != $address->getUser()) {
            $this->addFlash(
                'danger',
                "Bien essayer"
            );
            return $this->redirectToRoute('account_address', [
            ]);
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $address->setUser($this->getUser());

            $this->manager->persist($address);
            $this->manager->flush();

            // $this->addFlash(
            //     'success',
            //     'L\' '
            // )
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/add_addresses.html.twig', [
            'form' => $form->createView() // N'oublie pas le .createView() ou juste $form selon ta version de Symfony
        ]);
    }

    #[Route('/compte/supprimer-adresses/{id}', name: 'account_address_delete')]
    public function delete(Address $address): Response
    {
        if ($this->getUser() != $address->getUser()) {
            $this->addFlash(
                'danger',
                "Bien essayer"
            );
            return $this->redirectToRoute('account_address', [
            ]);
        }
        $this->manager->remove($address);
        $this->manager->flush();

        return $this->render('account/addresses.html.twig', [
            'addresses' => [],
        ]);
    }
}
