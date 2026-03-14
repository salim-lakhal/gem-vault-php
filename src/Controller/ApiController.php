<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Gemstone;
use App\Repository\GalleryRepository;
use App\Repository\GemstoneRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'app_api_')]
class ApiController extends AbstractController
{
    #[Route('/gemstones', name: 'gemstones', methods: ['GET'])]
    public function listGemstones(Request $request, GemstoneRepository $gemstoneRepository): JsonResponse
    {
        $type = $request->query->get('type');
        $color = $request->query->get('color');
        $rarity = $request->query->get('rarity');
        $page = max(1, $request->query->getInt('page', 1));
        $limit = max(1, $request->query->getInt('limit', 20));

        $qb = $gemstoneRepository->search(null, $type, $color, $rarity, null);
        $qb->setFirstResult(($page - 1) * $limit)->setMaxResults($limit);

        $paginator = new Paginator($qb);
        $total = count($paginator);
        $totalPages = (int) ceil($total / $limit);

        $data = [];
        foreach ($paginator as $gemstone) {
            $data[] = $this->serializeGemstone($gemstone);
        }

        return $this->json([
            'data' => $data,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    #[Route('/gemstones/{id}', name: 'gemstone_show', methods: ['GET'])]
    public function showGemstone(GemstoneRepository $gemstoneRepository, int $id): JsonResponse
    {
        $gemstone = $gemstoneRepository->find($id);

        if (!$gemstone) {
            return $this->json(['error' => 'Gemstone not found'], 404);
        }

        return $this->json($this->serializeGemstone($gemstone));
    }

    #[Route('/galleries', name: 'galleries', methods: ['GET'])]
    public function listGalleries(GalleryRepository $galleryRepository): JsonResponse
    {
        $galleries = $galleryRepository->findBy(['published' => true]);

        $data = array_map(fn(Gallery $gallery) => [
            'id' => $gallery->getId(),
            'name' => $gallery->getName(),
            'description' => $gallery->getDescription(),
            'isPublic' => $gallery->isPublic(),
            'gemstonesCount' => $gallery->getGemstones()->count(),
        ], $galleries);

        return $this->json($data);
    }

    #[Route('/galleries/{id}', name: 'gallery_show', methods: ['GET'])]
    public function showGallery(GalleryRepository $galleryRepository, int $id): JsonResponse
    {
        $gallery = $galleryRepository->find($id);

        if (!$gallery) {
            return $this->json(['error' => 'Gallery not found'], 404);
        }

        $gemstones = array_map(
            fn(Gemstone $g) => $this->serializeGemstone($g),
            $gallery->getGemstones()->toArray()
        );

        return $this->json([
            'id' => $gallery->getId(),
            'name' => $gallery->getName(),
            'description' => $gallery->getDescription(),
            'isPublic' => $gallery->isPublic(),
            'gemstones' => $gemstones,
        ]);
    }

    private function serializeGemstone(Gemstone $gemstone): array
    {
        return [
            'id' => $gemstone->getId(),
            'name' => $gemstone->getName(),
            'description' => $gemstone->getDescription(),
            'type' => $gemstone->getType(),
            'weight' => $gemstone->getWeight(),
            'estimatedValue' => $gemstone->getEstimatedValue(),
            'color' => $gemstone->getColor(),
            'origin' => $gemstone->getOrigin(),
            'rarity' => $gemstone->getRarity(),
            'acquisitionDate' => $gemstone->getAcquisitionDate()?->format('Y-m-d'),
            'imageName' => $gemstone->getImageName(),
        ];
    }
}
