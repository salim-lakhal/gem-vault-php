<?php

namespace App\Form;

use App\Entity\GemCollection;
use App\Entity\Gemstone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class GemstoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('type', TextType::class)
            ->add('weight', NumberType::class)
            ->add('estimatedValue', NumberType::class, [
                'required' => false,
            ])
            ->add('acquisitionDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('color', TextType::class, [
                'required' => false,
            ])
            ->add('origin', TextType::class, [
                'required' => false,
            ])
            ->add('rarity', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Select rarity',
                'choices' => [
                    'Common' => 'Common',
                    'Uncommon' => 'Uncommon',
                    'Rare' => 'Rare',
                    'Very Rare' => 'Very Rare',
                    'Legendary' => 'Legendary',
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_link' => false,
            ])
            ->add('collection', EntityType::class, [
                'class' => GemCollection::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gemstone::class,
        ]);
    }
}
