<?php

namespace App\Controller;

use App\Entity\Member;
use App\Repository\GemGalleryRepository;
use App\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    // Afficher la liste des membres
    #[Route('/member', name: 'app_member')]
    public function index(MemberRepository $memberRepository): Response
    {
        $members = $memberRepository->findAll();  // Récupérer tous les membres

        return $this->render('member/index.html.twig', [
            'members' => $members,
        ]);
    }

    // Afficher un membre spécifique
    #[Route('/gem-gallery/{memberID}', name: 'app_gem_gallery_show', methods: ['GET'])]
    public function show(int $memberID, MemberRepository $memberRepository, GemGalleryRepository $gemGalleryRepository): Response
    {
        // Récupérer le membre avec l'ID fourni dans l'URL
        $member = $memberRepository->find($memberID);
    
        if (!$member) {
            throw $this->createNotFoundException('Le membre avec l\'ID ' . $memberID . ' n\'existe pas.');
        }
    
        // Récupérer les galeries associées au membre
        $gemGalleries = $gemGalleryRepository->findBy(['creator' => $member]);
    
        return $this->render('member/show.html.twig', [
            'member' => $member,
            'gem_galleries' => $gemGalleries,  // Passer les galeries associées au membre
        ]);
    }
}
