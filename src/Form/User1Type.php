<?php

namespace App\Form;

use App\Entity\User1;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;
use function Sodium\add;

class User1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            //->add('roles')
           // ->add('password')
            ->add('CIN')
           // ->add('imageFile', VichImageType::class,[
            //    'attr' => ['class' => 'rounded-circle mt-5','width'=>'150px'],
           // ])
           ->add('imageFile', FileType::class, [
               //'label' => '',

               // unmapped means that this field is not associated to any entity property
               //'mapped' => false,

               // make it optional so you don't have to re-upload the PDF file
               // every time you edit the Product details
               'required' => false,

               // unmapped fields can't define their validation using annotations
               // in the associated entity, so you can use the PHP constraint classes
               'constraints' => [
                   new File([
                       'maxSize' => '1024k',

                       'mimeTypesMessage' => 'Please upload a valid picture',
                   ])
               ],
           ])
            ->add('FirstName')
            ->add('LastName')
            ->add('Password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,

                'attr' => ['autocomplete' => 'new-password'],
                'required'   => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('Birthdate',  DateType::class, [
                'widget' => 'single_text'
            ])


            //->add('updatedAt')
            //->add('isVerified')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User1::class,
        ]);
    }
}
