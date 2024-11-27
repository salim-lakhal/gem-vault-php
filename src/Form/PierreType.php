<?php

namespace App\Form;

use App\Entity\Ecrin;
use App\Entity\GemGallery;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Entity\Pierre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PierreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('typeDePierre')
            ->add('poids')
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_link' => false,
            ])
            ->add('valeurEstimee')
            ->add('dateAcquisition', null, [
                'widget' => 'single_text',
            ])
            ->add('ecrin', EntityType::class, [
                'class' => Ecrin::class,
                'choice_label' => 'id',
            ])
            ->add('gemGalleries', EntityType::class, [
                'disabled' => true,
                'class' => GemGallery::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pierre::class,
        ]);
    }
}
