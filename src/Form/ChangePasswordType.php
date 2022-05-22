<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,

                [
                    'disabled' => true,
                ]
            )
            ->add('email', EmailType::class,
            [
                'disabled' => true,
            ])
            ->add('old_password', PasswordType::class,
                [
                    'required' => true,
                    'label' => 'Mot de passe actuel',
                    'attr' => [
                        'placeholder' => 'Mot de passe actuel'
                    ],
                    'mapped' => false,
                ]
            )
            ->add('newpassword', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'required' => true,
                    'invalid_message' => 'Le nouveau mot de passe et la confirmation du nouveau mot de passe doivent être conforme.',
                    'mapped' => false,
                    'options' => [
                        'attr' => [ 'class' => 'password-field']
                    ],
                    'first_options' =>[
                        'label' => 'Nouveau mot de passe',
                        'attr' => ['placeholder' => 'Nouveau mot de passe']
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le nouveau mot de passe',
                        'attr' => ['placeholder' => 'Confirmer le nouveau mot de passe']
                    ]
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
