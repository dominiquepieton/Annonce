<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\AnnonceSearch;
use App\Form\AnnonceSearchType;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", name="annonce")
     */
    public function index(Request $request, AnnonceRepository $repository): Response
    {
        $search = new AnnonceSearch();
        $form = $this->createForm(AnnonceSearchType::class, $search);
        $form->handleRequest($request);

        $annonces = $repository->findAllVisibleQuery($search);

         
        return $this->render('annonce/index.html.twig', [
            'form' => $form->createView(),
            'annonces' => $annonces
        ]);
    }


    /**
     * Permet de voir l'annonce
     *
     * @Route("/annonce/{slug}", name="annonce_read")
     * @param AnnonceRepository $annonceRepository
     * @return void
     */
    public function readAnnonce($slug, AnnonceRepository $annonceRepository)
    {
        $ad = $annonceRepository->findBy(['slug' => $slug]);

        return $this->render('annonce/readAnnonce.html.twig', ['ad' => $ad]);
    }
}
