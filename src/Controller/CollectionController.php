<?php

namespace App\Controller;

use App\Entity\GemCollection;
use App\Repository\GemCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/collection', name: 'app_collection_')]
class CollectionController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(GemCollectionRepository $collectionRepository): Response
    {
        return $this->render('collection/index.html.twig', [
            'collections' => $collectionRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(GemCollection $collection): Response
    {
        return $this->render('collection/show.html.twig', [
            'collection' => $collection,
        ]);
    }
}
