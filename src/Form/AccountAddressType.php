<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'Nom de l\'addresse',
                    'required' => true,
                    'attr' =>[
                        'placeholder' => 'Maison'
                    ]
                ]
            )
            ->add('street', TextType::class,
                [
                    'label' => 'Nom de la rue',
                    'required' => true,
                    'attr' =>[
                        'placeholder' => '8T rue de bouvais'
                    ]
                ]
            )
            ->add('city', TextType::class,
                [
                    'label' => 'Ville',
                    'required' => true,
                    'attr' =>[
                        'placeholder' => 'Paris'
                    ]
                ]
            )
            ->add('codepostal', TextType::class,
                [
                    'label' => 'Code Postal',
                    'required' => true,
                    'attr' =>[
                        'placeholder' => 'XXXXX'
                    ]
                ]
            )
            ->add('country', CountryType::class,
                [
                    'label' => 'Pays',
                    'required' => true,
                    'attr' =>[
                        'placeholder' => 'France'
                    ]
                ]
            )
            ->add('phone', TelType::class,
                [
                    'label' => 'Téléphone',
                    'required' => true,
                    'attr' =>[
                        'placeholder' => '06X XXX XX XX'
                    ]
                ]
            )
            ->add('company', TextType::class,
                [
                    'label' => 'Nom de la societé',
                    'required' => false,
                    'attr' =>[
                        'placeholder' => 'AZUR SAS'
                    ]
                ]
            )
            // ->add('owner')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
