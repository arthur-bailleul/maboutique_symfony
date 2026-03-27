<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'=> false,
                'disabled'=> true
            ])
            ->add('lastName', TextType::class, [
                'label'=> false,
                'disabled'=> true
            ])
            ->add('firstName', TextType::class, [
                'label'=> false,
                'disabled'=> true
            ])
            // ->add('roles')
            ->add('oldPassword', PasswordType::class, [
                'label'=>false,
                'attr'=> ['placeholder'=>'Entrer votre ancien mot de passe']
            ])
            ->add('newPassword', PasswordType::class, [
                'label'=>false,
                'attr'=> ['placeholder'=>'Entrer votre nouveau mot de passe']
            ])
            ->add('confirmNewPassword', PasswordType::class, [
                'label'=>false,
                'attr'=> ['placeholder'=>'Confirmer votre nouveau mot de passe']
            ])
            // ->add('password')
            ->add('submit', SubmitType::class, [
                'label'=> "s'inscrire",
                'attr'=> ['class'=>'btn btn-primary col-12']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['changePassword']
        ]);
    }
}
