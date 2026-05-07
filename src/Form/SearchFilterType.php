<?php

namespace App\Form;

use App\Entity\SearchFilter;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => ['onchange' => 'this.closest("form").submit()']
            ])
            // -> add('submit', SubmitType::class, [
            //     'label' => 'Filtrer',
            //     'attr' => ['class' => 'btn-primary col-12']
            // ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
