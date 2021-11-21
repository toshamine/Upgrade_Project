<?php

namespace App\Controller\Admin;

use App\Entity\User1;
use App\Security\EmailVerifier;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
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
            TextareaField::new('imageFile')->hideOnIndex()
            ->setFormType(VichImageType::class)
                ->setLabel('Image'),
            BooleanField::new('isVerified'),





        ];
    }

}
