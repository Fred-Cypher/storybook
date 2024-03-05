<?php

namespace App\Form;

use App\Entity\Tales;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TalesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', options:[
                'attr' => [
                    'class' => 'form-control mt-2'
                ], 
                'label' => 'Titre : '
            ])
            ->add('explanation', options: [
                'attr' => [
                    'class' => 'form-control mt-2'
                ], 
                'label' => 'Note explicative (facultatif) :'
            ])
            ->add('digest', options: [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Résumé : '
            ])
            ->add('content', options: [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Conte : '
            ])
            ->add('drawings', FileType::class, [
                'label' => 'Dessin d\'illustration : ',
                'multiple' => true,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control mt-2'
                ]
            ])
            /*->add('drawings', options: [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Image d\'illustration :'
            ])*/
            ->add('caption', options: [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Légende de l\'image : '
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tales::class,
        ]);
    }
}
