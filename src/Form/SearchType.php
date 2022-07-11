<?php

namespace App\Form;


use App\Services\Search;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class  SearchType extends  AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
           ->add('name', TextType::class,
            [
                'label' => 'Nom du Produit',
                'required' => false,
                'attr' => [
                        'placeholder' => 'Nom',
                    ],
            ]
                )
            ->add('category', EntityType::class,
            [
                'label' => 'Categorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'expanded'=> true,
            ]
            );
        }

        /* ->add('location', EntityType::class,
        [
            'class' => Category::class,
            'choice_label' => 'name',
            'multiple' => true,
            'label' => 'Categorie',
            'required' => false,
            ] 
        );*/

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Search::class,
                'methods' => 'GET',
                'csrf_protection'=> false,
            ]);
        }
        public function getBlockPrefix()
        {
            return '';
        }
            
    }
    
   
        

