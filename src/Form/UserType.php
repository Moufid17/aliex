<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dd($options);
        //dd(($options['user_roles']));
        //$roles = array_map(null, $options['user_roles']);
        $roles = [];
        foreach ($options['user_roles'] as $key => $value) {
            $roles[$value] = $value;
        }
        //dd($roles);
        $builder
            ->add('email')
            ->add('roles', CollectionType::class, [
                'label' => 'Role',
/*                'entry_options' => Array(
                    //'label' => false,
                    'choices' => $roles
                ),
                'entry_type' => ChoiceType::class*/
            ])
/*            ->add('roles', ChoiceType::class, array(
                'label' => 'Rôle',
                //'choices' => $options,
                'choices' => array("a" => ["a" => "b"]),
                //'choice_label' => 'roles',
                //'multiple'  => false,
                //'expanded' => false,
                'required' => true,
            ))*/
            ->add('firstname')
            ->add('lastname')
            ->add('username')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
                'user_roles' => array(),
        ]);
    }
}
