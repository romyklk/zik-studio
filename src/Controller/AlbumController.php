<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlbumController extends AbstractController
{
    #[Route('/albums', name: 'app_albums')]
    public function index(AlbumRepository $albumRepository,PaginatorInterface $paginator, Request $request): Response
    {
     // $albums = $albumRepository->findBy([], ['titre' => 'ASC']);
 
        $albumsData = $albumRepository->allInfoAlbum();
        
        $albums = $paginator->paginate(
            $albumsData,
            $request->query->getInt('page', 1),
            6
        );

        $totalPages = ceil($albums->getTotalItemCount()/6);
        $currentPage = $albums->getCurrentPageNumber();


       if($currentPage > $totalPages) {
             return $this->redirectToRoute('app_albums'); 
        }

  
        return $this->render('album/index.html.twig', [
            'albums' => $albums
        ]);
    }

    #[Route('/album/{id}', name: 'app_album_show')]
    public function show(AlbumRepository $albumRepository, int $id): Response
    {
        $album = $albumRepository->find($id);

        if(!$album) {
            throw $this->createNotFoundException('Aucun album trouvé ');
        }

        return $this->render('album/fiche.html.twig', [
            'album' => $album
        ]);
    }
}
