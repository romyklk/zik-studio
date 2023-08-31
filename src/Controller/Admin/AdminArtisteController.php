<?php

namespace App\Controller\Admin;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminArtisteController extends AbstractController
{
    #[Route('/artistes', name: 'app_admin_artistes', methods: ['GET'])]
    public function index(ArtisteRepository $artisteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $artistesData = $artisteRepository->allInfoArtiste();

        $artistes = $paginator->paginate(
            $artistesData,
            $request->query->getInt('page', 1),
            6
        );

        $totalPages = ceil($artistes->getTotalItemCount() / 6);
        $currentPage = $artistes->getCurrentPageNumber();

        if ($currentPage > $totalPages) {
            return $this->redirectToRoute('app_admin_artistes', ['page' => 1]);
        }

        return $this->render('admin/artistes/index.html.twig', [
            'artistes' => $artistes,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage
        ]);
    }

    #[Route('/artiste/add', name: 'app_admin_artiste_add', methods: ['GET', 'POST'])]
    public function addArtiste(Request $request,EntityManagerInterface $em): Response
    {
        $artiste = new Artiste();
        $form = $this->createForm(ArtisteType::class,$artiste);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $artiste->setSlug((new Slugify())->slugify($artiste->getNom()));
            $artiste->setCreatedAt(new \DateTimeImmutable());
            $artiste->setPhoto('default.jpg');
            $em->persist($artiste);
            $em->flush();

        notyf()
            ->position('x', 'center')
            ->position('y', 'top')
            ->duration(5000)
            ->ripple(true)
            ->dismissible(true)
            ->addSuccess("L'artiste a bien été ajouté !");

            return $this->redirectToRoute('app_admin_artistes');
        }

        return $this->render('admin/artistes/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/artiste/edit/{slug}', name: 'app_admin_artiste_edit', methods: ['GET', 'POST'])]
    public function editArtiste($slug,Request $request,EntityManagerInterface $em,ArtisteRepository $artisteRepository): Response
    {
        $artiste = $artisteRepository->findOneBy(['slug' => $slug]);

        if(!$artiste)
        {
            throw $this->createNotFoundException("L'artiste n'existe pas !");
        }

        $form = $this->createForm(ArtisteType::class,$artiste);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $artiste->setSlug((new Slugify())->slugify($artiste->getNom()));
            $artiste->setCreatedAt(new \DateTimeImmutable());
            $artiste->setPhoto('https://loremflickr.com/g/320/240/paris,girl/all');
            $em->persist($artiste);
            $em->flush();

            //Vider le formulaire
            $form = $this->createForm(ArtisteType::class,$artiste);

        notyf()
            ->position('x', 'center')
            ->position('y', 'top')
            ->duration(5000)
            ->ripple(true)
            ->dismissible(true)
            ->addSuccess("L'artiste a bien été modifié !");

            return $this->redirectToRoute('app_admin_artistes');
        }

        return $this->render('admin/artistes/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/artiste/delete/{slug}', name: 'app_admin_artiste_delete', methods: ['GET'])]
    public function deleteArtiste($slug,EntityManagerInterface $em,ArtisteRepository $artisteRepository): Response
    {
        $artiste = $artisteRepository->findOneBy(['slug' => $slug]);

        $nbAlbums = $artiste->getAlbums()->count();

        if(!$artiste)
        {
            throw $this->createNotFoundException("L'artiste n'existe pas !");
        }
        $em->remove($artiste);
        $em->flush();

        notyf()
            ->position('x', 'center')
            ->position('y', 'top')
            ->duration(5000)
            ->ripple(true)
            ->dismissible(true)
            ->addSuccess("L'artiste a bien été supprimé !");

        return $this->redirectToRoute('app_admin_artistes');
    }



}
