<?php

namespace App\DataFixtures;

use App\Entity\Ecrin;
use App\Entity\Pierre;
use App\Entity\GemGallery;
use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadMembers($manager);
        $this->loadEcrinsAndPierres($manager);
        $this->loadGemGalleries($manager);
    }

    private function loadMembers(ObjectManager $manager)
    {
        foreach ($this->membersGenerator() as [$email, $plainPassword]) {
            $member = new Member();
            $password = $this->hasher->hashPassword($member, $plainPassword);
            $member->setEmail($email);
            $member->setPassword($password);

            $manager->persist($member);

            // Stocker la référence pour associer plus tard
            $this->addReference('member_' . $email, $member);
        }
        $manager->flush();
    }

    private function loadEcrinsAndPierres(ObjectManager $manager)
    {
        $ecrinNames = ['Écrin de luxe', 'Écrin vintage'];
        foreach ($ecrinNames as $key => $name) {
            $ecrin = new Ecrin();
            $ecrin->setNom($name);
            $ecrin->setDateDeCreation(new \DateTime());

            $manager->persist($ecrin);
            $this->addReference('ecrin_' . $key, $ecrin);

            // Ajouter des pierres associées
            $this->addPierresToEcrin($manager, $ecrin, $key);
        }
        $manager->flush();
    }

    private function addPierresToEcrin(ObjectManager $manager, Ecrin $ecrin, int $index)
    {
        $pierresData = [
            [
                ['Diamant', 'Un magnifique diamant de 1 carat.', 'Précieuse', 1.0],
                ['Rubis', 'Un rubis rouge éclatant.', 'Précieuse', 0.5],
            ],
            [
                ['Saphir', 'Un saphir bleu profond.', 'Précieuse', 0.8],
                ['Émeraude', 'Une émeraude verte de qualité supérieure.', 'Précieuse', 0.7],
            ],
        ];

        foreach ($pierresData[$index] as [$nom, $description, $type, $poids]) {
            $pierre = new Pierre();
            $pierre->setNom($nom)
                   ->setDescription($description)
                   ->setTypeDePierre($type)
                   ->setPoids($poids)
                   ->setDateAcquisition(new \DateTime())
                   ->setEcrin($ecrin);

            $manager->persist($pierre);
            $this->addReference('pierre_' . $nom, $pierre);
        }
    }

    private function loadGemGalleries(ObjectManager $manager)
    {
        $galleryNames = ['Galerie publique 1', 'Galerie privée 2'];
        foreach ($galleryNames as $key => $name) {
            $gallery = new GemGallery();
            $gallery->setNom($name);
            $gallery->setIsPublic($key % 2 === 0); // Alternance public/privé

            // Associer à un membre
            $memberReference = 'member_' . ($key === 0 ? 'olivier@localhost' : 'slash@localhost');
            $gallery->setCreator($this->getReference($memberReference));

            $manager->persist($gallery);
            $this->addReference('gallery_' . $key, $gallery);

            // Associer des pierres à la galerie
            $this->addPierresToGallery($manager, $gallery);
        }
        $manager->flush();
    }

    private function addPierresToGallery(ObjectManager $manager, GemGallery $gallery)
    {
        $pierres = ['Diamant', 'Rubis', 'Saphir', 'Émeraude'];
        foreach ($pierres as $pierreNom) {
            /** @var Pierre $pierre */
            $pierre = $this->getReference('pierre_' . $pierreNom);
            $gallery->addPierre($pierre);
        }
    }

    private function membersGenerator(): \Generator
    {
        yield ['olivier@localhost', '123456'];
        yield ['slash@localhost', '123456'];
    }
}

