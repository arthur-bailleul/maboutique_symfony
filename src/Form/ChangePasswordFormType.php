<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control rounded-3'
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir un mot de passe.',
                        ]),
                        new Length([
                            'min' => 12,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new PasswordStrength([
                            'message' => 'Le mot de passe choisi est trop faible. Veuillez utiliser un mélange de lettres, chiffres et caractères spéciaux.',
                        ]),
                        new NotCompromisedPassword([
                            'message' => 'Ce mot de passe a fuité dans une faille de données publique. Par sécurité, veuillez en choisir un autre.',
                        ]),
                    ],
                    'label' => 'Nouveau mot de passe',
                    'label_attr' => ['class' => 'form-label small fw-medium'],
                ],
                'second_options' => [
                    'label' => 'Confirmer le nouveau mot de passe',
                    'label_attr' => ['class' => 'form-label small fw-medium'],
                ],
                'invalid_message' => 'Les deux champs de mot de passe doivent être identiques.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
