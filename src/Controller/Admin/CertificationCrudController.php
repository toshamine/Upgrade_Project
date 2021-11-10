<?php

namespace App\Controller\Admin;

use App\Entity\Certification;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CertificationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Certification::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('title'),
            TextField::new('Company'),
            TextField::new('Difficulty'),
            TextField::new('Picture'),
            AssociationField::new('documents'),
            AssociationField::new('category')
        ];
    }

}