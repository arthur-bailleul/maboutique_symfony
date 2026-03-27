<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;

final class AccountPasswordController extends AbstractController
{

    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $manager;


    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->manager= $manager;
    }

    #[Route('/compte/modification-mot-de-passe', name: 'changePassword')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user);

        // dump($user);

        $form->handleRequest($request); // rempli le $user

        // // dump($user);
        // // dd($user);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd('inside if');
            $password = $user->getOldPassword();

            if (!$this->passwordHasher->isPasswordValid($user, $password)) {
                dd('old mdp incorrect');
            } else {
                dd('old mdp correct');
            }


            // return $this->redirectToRoute('home');
        }
        // dd('after if');

        return $this->render('account/account_password.html.twig', [
            'form' => $form
        ]);
    }
}
