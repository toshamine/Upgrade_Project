<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder->add('Name',TextType::class);

    }
    public function getName(){
        return "Category";
    }

}