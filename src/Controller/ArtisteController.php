<?php

namespace App\Controller;

use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtisteController extends AbstractController
{
    #[Route('/artistes', name: 'app_artistes', methods: ['GET'])]
    public function index(ArtisteRepository $artisteRepository,PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer tous les artistes
        //$artistes = $artisteRepository->findAll();

       $artistesData = $artisteRepository->allInfoArtiste();

        $artistes= $paginator->paginate(
            $artistesData,
            $request->query->getInt('page', 1),
            6
        );

        $totalPages = ceil($artistes->getTotalItemCount()/6);
        $currentPage = $artistes->getCurrentPageNumber();


       if($currentPage > $totalPages) {
             return $this->redirectToRoute('app_artistes'); 
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
