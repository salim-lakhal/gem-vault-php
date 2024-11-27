<?php

namespace App\Form;

use App\Entity\GemGallery;
use App\Entity\Member;
use App\Entity\Pierre;
use App\Repository\MemberRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\PierreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GemGalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupérer la galerie (objets passés via $options['data'])
        $gemGallery = $options['data'] ?? null;
        // Récupérer le membre propriétaire de la galerie
        $member = $gemGallery ? $gemGallery->getCreator() : null;

        $builder
            ->add('description')
            ->add('nom')
            ->add('published')
            ->add('isPublic', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'expanded' => true, // Affiche comme boutons radio
                'multiple' => false,
                'label' => 'Is Public?',
            ])

            ->add('creator', null, [
                'disabled' => true,
            ])
            // Utilisation de query_builder pour filtrer les objets liés au membre
            ->add('pierres', EntityType::class, [
                'class' => Pierre::class, // Entité des objets (ici, Pierre)
                'multiple' => true,       // Permet la sélection multiple
                'expanded' => true,       // Affiche les objets sous forme de checkbox
                'query_builder' => function (PierreRepository $er) use ($member) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.ecrin', 'e')    // Ajuster cette jointure selon votre modèle
                        ->leftJoin('e.member', 'm')   // Relier les objets au membre via l'inventaire
                        ->andWhere('m.id = :memberId')
                        ->setParameter('memberId', $member ? $member->getId() : 0); // Vérifier que $member est non nul
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GemGallery::class, // Associe le formulaire à l'entité GemGallery
        ]);
    }
}

