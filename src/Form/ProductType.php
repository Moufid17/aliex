<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'Nom du Produit',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Nom',
                    ],
                ]
            )
            ->add('imageFile',VichImageType::class, [
                'required' => true,
                // 'allow_delete' => true,
                // 'download_uri' => true,
                // 'image_uri' => true,
                // 'asset_helper' => true,
                'label' => 'Image',
                'attr' => [
                    'placeholder' => 'Choisir une image'
                ]
            ])
            ->add('weight', NumberType::class,
                [
                    'label' => 'Poids',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Poids du produit',
                    ],
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'label' => 'Description',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Description du produit',
                    ],    
                ]
            )
            ->add('price', MoneyType::class,
                [
                    'label' => 'Prix',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Prix du produit',
                    ],
                    'divisor' => 100,
                ]
            )
            ->add('category', EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'multiple' => false,
                    'label' => 'Categorie',
                    'required' => true,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
