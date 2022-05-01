<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
// use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class, 
                [
                    'label' => 'Username',
                    'required' => true,
                    'attr'  => [
                        'placeholder' => 'JDeo',
                    ]
                ]
            )
            ->add('firstname',TextType::class, 
                [
                    'label' => 'Firstname',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'John',
                    ]    
                ]
            )
            ->add('lastname',TextType::class, 
                [
                    'label' => 'Lastname',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Deo',
                    ]
                ]
            )
            ->add('email', EmailType::class,
                [
                    'label' =>'Email',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'john.deo@xyz.com',
                    ]    
                ]
            )
            ->add('password',PasswordType::class,[
                    'label' => 'Password',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Password'
                    ]
                ]
            )
            ->add('cpassword',PasswordType::class,[
                    'label' => 'Confirm Password',
                    'required' => true,
                    'mapped' => false,
                    'attr' => [
                        'placeholder' => 'Confirm Password'
                    ]
                ]
            )
            ->add('agreeTerms',CheckboxType::class,[
                    'label' => 'Accept our user terms',
                    'required' => true,
                    'mapped' => false,
                ]
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
