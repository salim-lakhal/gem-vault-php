<?php

namespace App\Controller;

use App\Entity\Gemstone;
use App\Form\GemstoneType;
use App\Repository\GemstoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gemstone', name: 'app_gemstone_')]
class GemstoneController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, GemstoneRepository $gemstoneRepository): Response
    {
        $q = $request->query->get('q', '');
        $type = $request->query->get('type', '');
        $color = $request->query->get('color', '');
        $rarity = $request->query->get('rarity', '');
        $origin = $request->query->get('origin', '');

        $page = max(1, $request->query->getInt('page', 1));
        $limit = 12;

        $qb = $gemstoneRepository->search($q, $type, $color, $rarity, $origin);
        $qb->setFirstResult(($page - 1) * $limit)->setMaxResults($limit);

        $paginator = new Paginator($qb);
        $totalItems = count($paginator);
        $totalPages = (int) ceil($totalItems / $limit);

        return $this->render('gemstone/index.html.twig', [
            'gemstones' => $paginator,
            'page' => $page,
            'totalPages' => $totalPages,
            'types' => $gemstoneRepository->findDistinctTypes(),
            'colors' => $gemstoneRepository->findDistinctColors(),
            'rarities' => $gemstoneRepository->findDistinctRarities(),
            'q' => $q,
            'type' => $type,
            'color' => $color,
            'rarity' => $rarity,
            'origin' => $origin,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Gemstone $gemstone): Response
    {
        return $this->render('gemstone/show.html.twig', [
            'gemstone' => $gemstone,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'], priority: 1)]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $gemstone = new Gemstone();
        $form = $this->createForm(GemstoneType::class, $gemstone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($gemstone);
            $em->flush();

            $this->addFlash('success', 'Gemstone created successfully.');

            return $this->redirectToRoute('app_gemstone_show', ['id' => $gemstone->getId()]);
        }

        return $this->render('gemstone/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Gemstone $gemstone, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(GemstoneType::class, $gemstone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Gemstone updated successfully.');

            return $this->redirectToRoute('app_gemstone_show', ['id' => $gemstone->getId()]);
        }

        return $this->render('gemstone/edit.html.twig', [
            'gemstone' => $gemstone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Gemstone $gemstone, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $gemstone->getId(), $request->request->get('_token'))) {
            $em->remove($gemstone);
            $em->flush();

            $this->addFlash('success', 'Gemstone deleted successfully.');
        }

        return $this->redirectToRoute('app_gemstone_index');
    }
}
