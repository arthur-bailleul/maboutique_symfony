<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Image;



class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // ID
        // Email
        // Password
        // Last Name
        // First Name
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name')->setLabel('Nom'),
            // TextEditorField::new('description'),
            // SlugField::new('slug')->setTargetFieldName('name'),
            ImageField::new('image')->setBasePath('uploads/')->setUploadDir('public/uploads/')->setFileConstraints(new Image(maxSize: '100m', mimeTypes: ['image/png', 'image/jpeg', 'image/webp']))->setRequired($pageName === Crud::PAGE_NEW),
            TextField::new('subtitle')->setLabel('Sous-titre'),
            // TextareaField::new('description'),
            TextEditorField::new('description'),
            MoneyField::new('price')->setLabel('Prix')->setCurrency('EUR'),
            AssociationField::new('category')
            // ChoiceField::new('roles')->setChoices(['Admin'=>'ROLE_ADMIN', 'User'=>'ROLE_USER'])->allowMultipleChoices(),
            // TextField::new('password')->setFormType(PasswordType::class)->onlyWhenCreating()->setRequired(true),
            // TextField::new('confirm_password')->setFormType(PasswordType::class)->onlyWhenCreating()->setRequired(true)
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
}
