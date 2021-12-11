<?php

namespace App\Controller\Admin;

use App\Entity\Cheaters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CheatersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cheaters::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('User'),
            TextField::new('Whitestest'),
            DateField::new('Date'),
        ];
    }

}
