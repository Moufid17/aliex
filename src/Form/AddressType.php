<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('street', TextType::class, [
                'label' => 'Rue',
                'required' => true
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => true
            ])
            ->add('codepostal', IntegerType::class, [
                'label' => 'Code postale',
                'required' => true
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'required' => true
            ])
            ->add('phone', IntegerType::class, [
                'label' => 'Numéro',
                'required' => true
            ])
            ->add('company', TextType::class, [
                'label' => 'Société',
                'required' => true
            ])
            ->add('owner', EntityType::class, [
                'class' => User::class,
                'label' => 'Utilisateur',
                'choice_label' => 'username',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
