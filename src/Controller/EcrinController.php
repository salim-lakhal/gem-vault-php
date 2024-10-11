<?php

namespace App\Controller;

use App\Entity\Ecrin;
use App\Repository\EcrinRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EcrinController extends AbstractController
{
    #[Route('/ecrin', name: 'app_ecrin')]
    public function index(): Response
    {
        return $this->render('ecrin/index.html.twig', [
            'controller_name' => 'EcrinController',
        ]);
    }
    
    
    #[Route('/ecrin/list', name: 'ecrin_list', methods: ['GET'])]
    public function list(EcrinRepository $ecrinRepository)
    {  $ecrins = $ecrinRepository->findAll();
    
    dump($ecrins);
    
    return $this->render('ecrin/list.html.twig',
        [ 'ecrins' => $ecrins ]
        );
    }
        
    
    #[Route('/ecrin/{id}', name: 'ecrin_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id) : Response
    {   
        $ecrinRepo = $doctrine->getRepository(Ecrin::class);
        $ecrin = $ecrinRepo->find($id);
        // Erreur 404 si l'ID n'existe pas
        if (!$ecrin) {
            throw $this->createNotFoundException('L\'écrin avec l\'ID ' . $id . ' n\'existe pas.');
        }
        
        return $this->render('ecrin/show.html.twig',
            [ 'ecrin'=> $ecrin ]
            );
    }
    
}
