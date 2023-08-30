<?php

namespace App\Form;

use App\Entity\Style;
use App\Entity\Artiste;
use PharIo\Manifest\Url;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArtisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'attr' => [
                    'placeholder' => 'Nom de l\'artiste'
                ],
                'label'=>false
            ])
            ->add('description',TextareaType::class,[
                'attr' => [
                    'rows' => 10,
                    'cols' => 50,
                    'placeholder' => 'Description de l\'artiste'
                ],
                'label'=>false
                
            ])
            ->add('siteWeb',UrlType::class,[
                'attr' => [
                    'placeholder' => 'Site web de l\'artiste : www.example.com'
                ],
                'label'=>false
            ])
            ->add('photo',TextType::class,['label'=>false])
               
            ->add('style',EntityType::class,[
                'class' => Style::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'expanded' => false,
                'label'=>false
            ])

            ->add('type',ChoiceType::class,[
                'label'=>false,
                'choices' => [
                    'Groupe' => 'Groupe',
                    'Solo' => 'Solo',
                    'dj' => 'dj',
                    'orchestre' => 'orchestre',
                    'duo' => 'duo',
                    'autre' => 'autre'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artiste::class,
        ]);
    }
}
