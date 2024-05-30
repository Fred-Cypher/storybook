<?php

namespace App\Form;

use App\Entity\RecentGames;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditRecentGameFormType extends AbstractType
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
            ->add('illustrations', FileType::class, [
                'label' => 'Image d\'illustration du jeu : ',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
            ])
            ->add('category', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check my-2 mx-3 d-flex justify-content-around'
                ],
                'label' => 'Catégorie(s) / genre : ',
                'choices' => [
                    'Action' => 'action',
                    'Aventure' => 'aventure',
                    'Point\'n click' => 'point\'n click',
                    'Réflexion' => 'reflexion',
                    'Ludo-éducatif' => 'ludo-educatif',
                    'RPG' => 'rpg',
                    'Plate-forme' => 'plate-forme',
                    'Roman graphique' => 'roman graphique',
                    'Indépendant' => 'independant'
                ],
                'multiple' => true,
                'expanded' => true
            ])
            ->add('year', options: [
                'attr' => [
                    'class' => 'form-control mt-2 col-md-2'
                ],
                'label' => 'Année de sortie du jeu : '
            ])
            ->add('pegi', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check my-2 mx-3 d-flex justify-content-around col-md-4'
                ],
                'label' => 'Âge minimal recommandé : ',
                'label_attr' => ['class' => 'mt-2'],
                'choices' => [
                    3 => '3',
                    7 => '7',
                    12 => '12',
                    16 => '16',
                    18 => '18'
                ],
                'multiple' => false,
                'expanded' => true
            ])
            ->add('notes', options: [
                'attr' => [
                    'class' => 'form-control mt-2 col-md-2'
                ],
                'label' => 'Notes sur le jeu : '
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
            'data_class' => RecentGames::class,
        ]);
    }
}
