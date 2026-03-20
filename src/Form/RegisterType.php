<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Mime\Email;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'required' => false
            ])
            // ->add('roles')
            -> add('firstName', TextType::class, [
                'label' => 'Prenom',
                'attr' => ['placeholder' => 'Entrer le prenom']])
            -> add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrer le nom']
            ])
            -> add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Entrer un mot de passe']
            ])
            -> add('confirm_password', PasswordType::class, [
                'label' => 'Confirme mot de passe',
                'attr' => ['placeholder' => 'Confirmer le mot de passe']
            ])
            -> add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn-primary col-12']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
