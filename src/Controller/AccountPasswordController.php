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
                $this->addFlash(
                    'danger',
                    "l'ancien mot de passe est incorrect"
                );
                // dd('old mdp incorrect');
                return $this->redirectToRoute('changePassword');
            } else {
                $newPassword = $user->getNewPassword();
                $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword );
                $user->setPassword($hashedPassword);

                // permet d'ajouter * objets dans la db d'un coup
                $this->manager->persist($user);
                $this->manager->flush();
                // dd('old mdp correct');

                $this->addFlash(
                    "success",
                    "votre mot de passe a bien ete modifier"
                );
                return $this->redirectToRoute('account');
            }


            // return $this->redirectToRoute('home');
        }

        // dd('after if');

        return $this->render('account/account_password.html.twig', [
            'form' => $form
        ]);
    }
}
