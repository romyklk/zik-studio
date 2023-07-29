<?php

namespace App\Controller;

use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtisteController extends AbstractController
{
    #[Route('/artistes', name: 'app_artistes', methods: ['GET'])]
    public function index(ArtisteRepository $artisteRepository): Response
    {
        // Récupérer tous les artistes
        $artistes = $artisteRepository->findAll();

        if (!$artistes) {
            throw $this->createNotFoundException('Aucun artiste trouvé');
        }

        return $this->render('artiste/index.html.twig', [
            'artistes' => $artistes,
        ]);
    }




    #[Route('/artiste/{slug}', name: 'app_artiste_show', methods: ['GET'])]
    public function show(ArtisteRepository $artisteRepository, string $slug): Response
    {
        $artiste = $artisteRepository->findOneBy(['slug' => $slug]);
        if (!$artiste) {
            throw $this->createNotFoundException('Aucun artiste trouvé');
        }
        return $this->render('artiste/fiche.html.twig', [
            'artiste' => $artiste
        ]);
    }
}
