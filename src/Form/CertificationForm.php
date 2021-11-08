<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CertificationForm extends AbstractType
{

    public function buildForm(FormBuilderInterface  $builder,array $option){
        $builder->add('Title',TextType::class)
                ->add('Company',TextType::class)
            ->add('Difficulty',TextType::class)
            ->add('Picture',TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Add ']);
    }

    public function getName(){
        return "Certification";
    }
}