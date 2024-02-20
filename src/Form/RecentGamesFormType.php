<?php

namespace App\Form;

use App\Entity\RecentGames;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecentGamesFormType extends AbstractType
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
                    'class' => 'form-control mt-2'
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
                'label' => 'Illustration représentant le jeu : '
            ])
            ->add('category', options: [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Catégorie(s) : '
            ])
            ->add('pegi', options: [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Âge minimum recommandé : '
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
            'data_class' => RecentGames::class,
        ]);
    }
}
