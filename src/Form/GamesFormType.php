<?php

namespace App\Form;

use App\Entity\Games;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('covers', FileType::class, [
            'label' => 'Jaquette du jeu : ',
            'multiple' => true,
            'mapped' => false,
            'required' => true,
            'attr' => [
                'class' => 'form-control mt-2'
            ],
            ])
            ->add('category', options: [
            'attr' => [
                'class' => 'form-control mt-2'
            ],
            'label' => 'Catégorie(s) / genre : (Action, Aventure,  Point\'n Click, Réflexion, Ludo-éducatif, RPG, Plate-forme, Roman graphique, Indépendant'
            ])
            /*->add('category', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check my-2 mx-3 d-flex justify-content-around'
                ],
                'label' => 'Catégorie(s) / genre : ',
                'choices' => [
                    'Action' => 'Action',
                    'Aventure' => 'Aventure',
                    'Point\'n click' => 'Point\' click',
                    'Reflexion' => 'Réflexion',
                    'Ludo-educatif' => 'Ludo-éducatif',
                    'RPG' => 'RPG',
                    'Plate-forme' => 'Plate-forme',
                    'Roman graphique' => 'Roman graphique',
                    'Independant' => 'Indépendant'
                ],
                'multiple' => true,
                'expanded' => true
            ])*/
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
                'label' => 'Support : ',
                'choices' => [
                    'PC' => 'PC',
                    'DS' => 'DS / 3DS'
                ],
                'multiple' => false,
                'expanded' => true
            ])
            
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Games::class,
        ]);
    }
}
