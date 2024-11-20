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
#[Route('/member/{id}', name: 'app_member_show')]
public function show(Member $member, GemGalleryRepository $gemGalleryRepository): Response
{
    // Récupérer les galeries du membre
    $gemGalleries = $gemGalleryRepository->findBy(['member' => $member]);

    return $this->render('member/show.html.twig', [
        'member' => $member,
        'gem_galleries' => $gemGalleries,  // Passer les galeries associées au membre
    ]);
}
}
