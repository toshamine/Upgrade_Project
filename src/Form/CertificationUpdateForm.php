<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Certification;
use App\Entity\Company;
use App\Entity\Difficulty;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class CertificationUpdateFormForm extends AbstractType
{

    public function buildForm(FormBuilderInterface  $builder,array $option){
        $builder->add('Title',TextType::class)

            ->add('Company',EntityType::class,['class'=>Company::class,'choice_label'=>'Name'])


            ->add('Difficulty',EntityType::class,['class'=>Difficulty::class,'choice_label'=>'Name'])

            ->add('Category',EntityType::class,['class'=>Category::class,'choice_label'=>'Name'])



->add('Picture', FileType::class, [
                'label' => 'Picture',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,


    'required'=>false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                       'maxSize' => '1024k',

                        'mimeTypesMessage' => 'Please Upload A Valid Picture',
                    ])
                ],
            ])
        ->add('documents',FileType::class,['label' => 'Documents',
            'multiple'=>true,
            'mapped'=>false,
            'required'=>false]) ;
    }
    public function configureOptions(OptionsResolver  $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Certification::class,
        ]);
    }
}