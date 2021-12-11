<?php

namespace App\Controller\Admin;

use App\Entity\User1;
use App\Security\EmailVerifier;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;
use Vich\UploaderBundle\Form\Type\VichImageType;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class User1CrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User1::class;
    }


    public function configureFields(string $pageName): iterable
    {


        return [
            IntegerField::new('id')->onlyOnIndex(),
            ImageField::new('picture')->onlyOnIndex()
                ->setBasePath($this->getParameter("app.path.product_images"))
                ->setFormType(VichImageType::class)
                ->setLabel('Image'),
            TextField::new('CIN'),
            ArrayField::new('roles'),
            EmailField::new('email'),
            TextField::new('password')->hideOnIndex()->hideWhenUpdating(),
            TextField::new('Firstname'),
            //TextField::new('roles'),
            TextField::new('Lastname'),
            DateField::new('birthdate'),
            AssociationField::new('Category')->onlyWhenUpdating(),
            TextareaField::new('imageFile')->hideOnIndex()
            ->setFormType(VichImageType::class)
                ->setLabel('Image'),
            BooleanField::new('isVerified'),





        ];
    }



    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users')

            // in addition to a string, the argument of the singular and plural label methods
            // can be a closure that defines two nullable arguments: entityInstance (which will
            // be null in 'index' and 'new' pages) and the current page name
            ->setPaginatorPageSize(5)
            ->setSearchFields(['email', 'Birthdate','CIN','FirstName','LastName'])




            ;
    }

}
