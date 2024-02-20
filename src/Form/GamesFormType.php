<?php

namespace App\Form;

use App\Entity\Games;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GamesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', options: [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Titre : '
            ])
            ->add('subtitle', options: [
                'attr' => [
                    'class' => 'form-control mt-2 col-md-6'
                ],
                'label' => 'Sous-titre : '
            ])
            ->add('editor', options: [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Développeur / Éditeur : '
            ])
            ->add('cover', options: [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Image de jaquette : '
            ])
            ->add('category', options: [
            'attr' => [
                'class' => 'form-control mt-2'
            ],
            'label' => 'Catégorie(s) : '
        ])
            ->add('pegi', options: [
            'attr' => [
                'class' => 'form-control mt-2 col-md-2'
            ],
            'label' => 'Âge minimal recommandé : '
        ])
            ->add('support', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check my-2 mx-3 d-flex justify-content-around col-md-2'
                ], 
                'label' => 'Support',
                'choices' => [
                    'PC' => 'PC',
                    'DS' => 'DS / 3DS'
                ],
                'multiple' => false,
                'expanded' => true
            ])
            
            /**'form-check d-flex justify-content-evenly align-items-center col-sm-12 col-md-8 col-lg-7',*/
            ->add('configuration', options: [
            'attr' => [
                'class' => 'form-control mt-2'
            ],
            'label' => 'Configuration minimale : '
        ])
        
            ->add('digest', options: [
            'attr' => [
                'class' => 'form-control mt-2'
            ],
            'label' => 'Résumé : '
        ])
            ->add('user', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'nickname',
                'label' => 'Utilisateur : '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Games::class,
        ]);
    }
}
