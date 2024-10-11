<?php

namespace App\Controller;


use App\Entity\Pierre;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PierreController extends AbstractController
{
    #[Route('/pierre/{id}', name: 'pierre_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id): Response
    {
        $pierreRepo = $doctrine->getRepository(Pierre::class);
        $pierre= $pierreRepo->find($id);

        // Vérification si la pierre a été trouvée
        if (!$pierre) {
            throw $this->createNotFoundException('La pierre avec l\'ID ' . $id . ' n\'existe pas.');
        }
        
        return $this->render('pierre/show.html.twig',
            [ 'pierre'=> $pierre ]
            );
    }
}

