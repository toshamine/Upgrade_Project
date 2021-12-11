<?php

namespace App\Controller\Admin;

use App\Entity\RDV;
use Doctrine\DBAL\Types\TextType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RDVCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RDV::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('User'),
            DateField::new('Date'),
            AssociationField::new('Certification'),
            TextField::new('Status')
        ];
    }

}
