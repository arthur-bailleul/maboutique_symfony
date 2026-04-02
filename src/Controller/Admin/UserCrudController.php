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

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the visible title at the top of the page and the content of the <title> element
            // it can include these placeholders:
            //   %entity_name%, %entity_as_string%,
            //   %entity_id%, %entity_short_id%
            //   %entity_label_singular%, %entity_label_plural%
            // ->setPageTitle('index', '%entity_label_plural% listing')
            ->setPageTitle('index', "Utilisateurs") // titre de la page index
            ->setPageTitle('edit', "Modifier l'utilisateur")
            ->setFormOptions([
                'validation_groups' => ['registration']
            ])

            // you can pass a PHP closure as the value of the title
            // ->setPageTitle('new', fn () => new \DateTime('now') > new \DateTime('today 13:00') ? 'New dinner' : 'New lunch')

            // in DETAIL and EDIT pages, the closure receives the current entity
            // as the first argument
            // ->setPageTitle('detail', fn (Product $product) => (string) $product)
            // ->setPageTitle('edit', fn (Category $category) => sprintf('Editing <b>%s</b>', $category->getName()))

            // the help message displayed to end users (it can contain HTML tags)
            // ->setHelp('edit', '...')
        ;
    }



    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL) // dans les ... c'est le consulter
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            // ->remove(Crud::PAGE_INDEX, Action::NEW) // suppr le bouton creer
            // ->remove(Crud::PAGE_DETAIL, Action::EDIT) // suppr le bouton creer
            // ->remove(Crud::PAGE_DETAIL, Action::DELETE) // suppr le bouton creer
        ;
    }
    // /*
    public function configureFields(string $pageName): iterable
    {
        // ID
        // Email
        // Password
        // Last Name
        // First Name
        return [
            IdField::new('id')->hideWhenCreating()->hideWhenUpdating(),
            // IdField::new('id'),
            // TextField::new('title'),
            TextField::new('Email'),
            // TextEditorField::new('description'),
            TextField::new('FirstName')->setLabel('Prenom'),
            TextField::new('LastName')->setLabel('Nom'),
            ChoiceField::new('roles')->setChoices(['Admin'=>'ROLE_ADMIN', 'User'=>'ROLE_USER'])->allowMultipleChoices(),
            TextField::new('password')->setFormType(PasswordType::class)->onlyWhenCreating(),
            TextField::new('confirm_password')->setFormType(PasswordType::class)->onlyWhenCreating()
        ];
    }
    // */
}
