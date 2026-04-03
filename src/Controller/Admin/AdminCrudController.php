<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;


use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ArrayFilter;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityRepositoryInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class AdminCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL) // dans les ... c'est le consulter
            // ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            // ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN)
            // ->remove(Crud::PAGE_INDEX, Action::NEW) // suppr le bouton creer
            // ->remove(Crud::PAGE_DETAIL, Action::EDIT) // suppr le bouton creer
            // ->remove(Crud::PAGE_DETAIL, Action::DELETE) // suppr le bouton creer
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        // ID
        // Email
        // Password
        // Last Name
        // First Name
        return [
            IdField::new('id')->hideWhenCreating()->hideWhenUpdating(),
            TextField::new('Email'),
            TextField::new('FirstName')->setLabel('Prenom'),
            TextField::new('LastName')->setLabel('Nom'),
            ChoiceField::new('roles')->setChoices(['Admin'=>'ROLE_ADMIN', 'User'=>'ROLE_USER'])->allowMultipleChoices(),
            TextField::new('password')->setFormType(PasswordType::class)->onlyWhenCreating()->setRequired(true),
            TextField::new('confirm_password')->setFormType(PasswordType::class)->onlyWhenCreating()->setRequired(true)
        ];
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->container->get(EntityRepositoryInterface::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere("entity.roles like '%ROLE_ADMIN%'");
        return $response;
        // return $this->container->get(EntityRepositoryInterface::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
    }
}
