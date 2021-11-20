<?php

namespace App\Controller\Admin;

use App\Entity\WhiteTest;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use Symfony\Component\Validator\Constraints\Time;

class WhiteTestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WhiteTest::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('questions'),
            TimeField::new('time'),
            TimeField::new('limit_time'),


        ];
    }

}
