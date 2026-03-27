<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;



final class RegisterController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $manager;


    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->manager= $manager;
    }





    #[Route('/register', name: 'register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        // dump($user);

        $form->handleRequest($request); // rempli le $user

        // dump($user);
        // dd($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $user->getPassword();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            // permet d'ajouter * objets dans la db d'un coup
            $this->manager->persist($user);
            $this->manager->flush();


            // $this->addFlash(
            //     'notice',
            //     'Your changes were noticed!'
            // );

            $this->addFlash(
                'success',
                'Votre compte a bien ete cree!'
            );

            // $this->addFlash(
            //     'danger',
            //     'Your changes were in danger!'
            // );

            return $this->redirectToRoute('home');
        }

        return $this->render('register/index.html.twig', [
            // 'controller_name' => 'RegisterController',
            'form' => $form,
        ]);
    }
}
