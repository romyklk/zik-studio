<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    #[Route('/albums', name: 'app_albums')]
    public function index(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findAll();

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
