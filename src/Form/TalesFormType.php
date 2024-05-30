<?php

namespace App\Form;

use App\Entity\Tales;
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
                    'class' => 'form-control mt-1'
                ], 
                'label' => 'Titre : ', 
                'label_attr' => ['class' => 'mt-2'] 
            ])
            ->add('explanation', options: [
                'attr' => [
                    'class' => 'form-control mt-1'
                ], 
                'label' => 'Note explicative (facultatif) :',
                'label_attr' => ['class' => 'mt-2'] 
            ])
            ->add('digest', options: [
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'label' => 'Résumé : ', 
                'label_attr' => ['class' => 'mt-2'] 
            ])
            ->add('content', options: [
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'label' => 'Conte : ', 
                'label_attr' => ['class' => 'mt-2'] 
            ])
            ->add('drawings', FileType::class, [
                'label' => 'Dessin d\'illustration : ',
                'label_attr' => ['class' => 'mt-2'] ,
                'multiple' => true,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control mt-1'
                ]
            ])
            ->add('caption', options: [
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'label' => 'Légende de l\'image : ', 
                'label_attr' => ['class' => 'mt-2'] 
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
