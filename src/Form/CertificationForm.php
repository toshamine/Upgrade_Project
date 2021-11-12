<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Certification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CertificationForm extends AbstractType
{

    public function buildForm(FormBuilderInterface  $builder,array $option){
        $builder->add('Title',TextType::class)
                ->add('Company',TextType::class)
            ->add('Difficulty',TextType::class)


            ->add('Picture', FileType::class, [
                'label' => 'Picture',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                       'maxSize' => '1024k',

                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ]);
    }
    public function configureOptions(OptionsResolver  $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Certification::class,
        ]);
    }
}