<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
            ->add('password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'required' => true,
                    'invalid_message' => 'le mot de passe et la confirmation du mot de passe doivent être conforme.',
                    'options' => [
                        'attr' => ['class' => 'password-field']
                    ],
                    'first_options' => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmer mot de passe']
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
