<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label'=> false, 'attr'=> [
                'placeholder' => 'Entrer un nom pour l\'adresse',
                'required' => false
            ]])
            ->add('firstname', TextType::class, ['label'=> false, 'attr'=> [
                'placeholder' => 'Entrer votre prenom',
                'required' => false
            ]])
            ->add('lastname', TextType::class, ['label'=> false, 'attr'=> [
                'placeholder' => 'Entrer votre nom',
                'required' => false
            ]])
            ->add('company', TextType::class, ['label'=> false, 'attr'=> [
                'placeholder' => 'Entrer une entreprise (Optionel)',
                'required' => false
            ]])
            ->add('address', TextType::class, ['label'=> false, 'attr'=> [
                'placeholder' => 'Entrer une adresse',
                'required' => false
            ]])
            ->add('postal', TextType::class, ['label'=> false, 'attr'=> [
                'placeholder' => 'Entrer un code postal',
                'required' => false
            ]])
            ->add('city', TextType::class, ['label'=> false, 'attr'=> [
                'placeholder' => 'Entrer une ville',
                'required' => false
            ]])
            ->add('country', CountryType::class, ['label'=> false, 'placeholder' => 'Choisissez un pays'])
            ->add('phone', TextType::class, ['label'=> false, 'attr'=> [
                'placeholder' => 'Entrer votre numero',
                'required' => false
            ]])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
