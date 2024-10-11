<?php

namespace App\DataFixtures;

use App\Entity\Ecrin;
use App\Entity\Pierre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture{
    public function load(ObjectManager $manager)
    {
        $this->loadRessources($manager);
    }

    private function loadRessources(ObjectManager $manager)
    {
        // Création de l'écrin 1
        $ecrin1 = new Ecrin();
        $ecrin1->setNom("Écrin de luxe");
        $ecrin1->setDateDeCreation(new \DateTime()); 
        $manager->persist($ecrin1);
        
        // Création des pierres précieuses pour le premier écrin
        $pierre1 = new Pierre();
        $pierre1->setNom("Diamant");
        $pierre1->setDescription("Un magnifique diamant de 1 carat.");
        $pierre1->setTypeDePierre("Précieuse"); 
        $pierre1->setPoids(1.0); 
        $pierre1->setDateAcquisition(new \DateTime('2020-01-01')); 
        $pierre1->setEcrin($ecrin1);
        $manager->persist($pierre1);
        
        $pierre2 = new Pierre();
        $pierre2->setNom("Rubis");
        $pierre2->setDescription("Un rubis rouge éclatant.");
        $pierre2->setTypeDePierre("Précieuse"); 
        $pierre2->setPoids(0.5); 
        $pierre2->setDateAcquisition(new \DateTime('2021-05-15'));
        $pierre2->setEcrin($ecrin1);
        $manager->persist($pierre2);
    
        // Création de l'écrin 2
        $ecrin2 = new Ecrin();
        $ecrin2->setNom("Écrin vintage");
        $ecrin2->setDateDeCreation(new \DateTime()); 
        $manager->persist($ecrin2);
        
        // Création des pierres précieuses pour le deuxième écrin
        $pierre3 = new Pierre();
        $pierre3->setNom("Saphir");
        $pierre3->setDescription("Un saphir bleu profond.");
        $pierre3->setTypeDePierre("Précieuse"); 
        $pierre3->setPoids(0.8); 
        $pierre3->setDateAcquisition(new \DateTime('2022-03-20')); 
        $pierre3->setEcrin($ecrin2);
        $manager->persist($pierre3);
        
        $pierre4 = new Pierre();
        $pierre4->setNom("Émeraude");
        $pierre4->setDescription("Une émeraude verte de qualité supérieure.");
        $pierre4->setTypeDePierre("Précieuse"); 
        $pierre4->setPoids(0.7); 
        $pierre4->setDateAcquisition(new \DateTime('2023-07-10')); 
        $pierre4->setEcrin($ecrin2);
        $manager->persist($pierre4);
    
        
        $manager->flush();
    }
    

}

