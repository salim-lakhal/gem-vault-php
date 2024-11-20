<?php

namespace App\Controller;

use App\Entity\GemGallery;
use App\Entity\Pierre;
use App\Form\GemGalleryType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\GemGalleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gem/gallery')]
final class GemGalleryController extends AbstractController
{
    #[Route(name: 'app_gem_gallery_index', methods: ['GET'])]
    public function index(GemGalleryRepository $gemGalleryRepository): Response
    {
        return $this->render('gem_gallery/index.html.twig', [
            'gem_galleries' => $gemGalleryRepository->findAll(),
        ]);
    }
    

    #[Route('/new', name: 'app_gem_gallery_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gemGallery = new GemGallery();
        $form = $this->createForm(GemGalleryType::class, $gemGallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gemGallery);
            $entityManager->flush();

            return $this->redirectToRoute('app_gem_gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gem_gallery/new.html.twig', [
            'gem_gallery' => $gemGallery,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gem_gallery_show', methods: ['GET'])]
    public function show(GemGallery $gemGallery): Response
    {
        return $this->render('gem_gallery/show.html.twig', [
            'gem_gallery' => $gemGallery,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gem_gallery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GemGallery $gemGallery, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GemGalleryType::class, $gemGallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_gem_gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gem_gallery/edit.html.twig', [
            'gem_gallery' => $gemGallery,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gem_gallery_delete', methods: ['POST'])]
    public function delete(Request $request, GemGallery $gemGallery, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gemGallery->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($gemGallery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gem_gallery_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pierre/{id}', name: 'app_pierre_show', methods: ['GET'])]
    public function pierreshow(ManagerRegistry $doctrine, $id): Response
    {
        $pierreRepo = $doctrine->getRepository(Pierre::class);
        $pierre= $pierreRepo->find($id);

        $gemGalleryRepo = $doctrine->getRepository(GemGallery::class);
        $gemGallery= $gemGalleryRepo->find($id);

        if (!$gemGallery->getPierres()->contains($pierre)) {
        throw $this->createNotFoundException("Cette pierre n'est pas dans cette galerie.");
    }

        // Vérification si la pierre a été trouvée
        if (!$pierre) {
            throw $this->createNotFoundException('La pierre avec l\'ID ' . $id . ' n\'existe pas.');
        }
        
        return $this->render('pierre/show.html.twig',
            [ 'pierre'=> $pierre ]
            );
    }

}
