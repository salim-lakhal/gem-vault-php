<?php

namespace App\Controller;

use App\Entity\Pierre;
use App\Form\PierreType;
use App\Entity\GemGallery;
use App\Repository\PierreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pierre')]
final class PierreController extends AbstractController
{
    #[Route(name: 'app_pierre_index', methods: ['GET'])]
    public function index(PierreRepository $pierreRepository): Response
    {
        return $this->render('pierre/index.html.twig', [
            'pierres' => $pierreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pierre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, GemGallery $gemGallery): Response
    {
        $pierre = new Pierre();
        $pierre->addGemGallery( $gemGallery);
        $form = $this->createForm(PierreType::class, $pierre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pierre);
            $entityManager->flush();

            return $this->redirectToRoute('app_gemGallery_show', ['id' => $gemGallery->getId()],Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('app_gemGallery_show',
                                      ['id' => $gemGallery->getId()],
                                      Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_pierre_show', methods: ['GET'])]
    public function show(Pierre $pierre): Response
    {
        return $this->render('pierre/show.html.twig', [
            'pierre' => $pierre,
        ]);
    }

    #[Route('/{gemGalleryId}/pierre/{id}/edit', name: 'app_pierre_edit', methods: ['GET', 'POST'])]
        public function edit(
            Request $request,
            Pierre $pierre,
            EntityManagerInterface $entityManager,
            int $gemGalleryId
        ): Response {
            // Récupérer la galerie associée
            $gemGallery = $entityManager->getRepository(GemGallery::class)->find($gemGalleryId);

            // Vérifiez si la pierre est bien dans la galerie
            if (!$gemGallery || !$pierre->getGemGalleries()->contains($gemGallery)) {
                throw $this->createNotFoundException('This Pierre does not belong to the specified GemGallery.');
            }

            $form = $this->createForm(PierreType::class, $pierre);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('app_gem_gallery_show', [
                    'id' => $gemGallery->getId(),
                ], Response::HTTP_SEE_OTHER);
            }

            return $this->render('pierre/edit.html.twig', [
                'pierre' => $pierre,
                'form' => $form,
                'gemGallery' => $gemGallery,
            ]);
        }

        #[Route('/{gemGalleryId}/pierre/{id}', name: 'app_pierre_delete', methods: ['POST'])]
        public function delete(
            Request $request,
            Pierre $pierre,
            EntityManagerInterface $entityManager,
            int $gemGalleryId
        ): Response {
            // Récupérer la galerie associée
            $gemGallery = $entityManager->getRepository(GemGallery::class)->find($gemGalleryId);
        
            // Vérifiez si la pierre est bien dans la galerie
            if (!$gemGallery || !$pierre->getGemGalleries()->contains($gemGallery)) {
                throw $this->createNotFoundException('This Pierre does not belong to the specified GemGallery.');
            }
        
            if ($this->isCsrfTokenValid('delete'.$pierre->getId(), $request->request->get('_token'))) {
                $entityManager->remove($pierre);
                $entityManager->flush();
            }
        
            return $this->redirectToRoute('app_gem_gallery_show', [
                'id' => $gemGallery->getId(),
            ], Response::HTTP_SEE_OTHER);
        }
}

