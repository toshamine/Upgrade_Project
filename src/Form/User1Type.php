<?php

namespace App\Form;

use App\Entity\User1;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('FirstName')
            ->add('LastName')
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
