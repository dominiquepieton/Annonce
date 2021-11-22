<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", name="annonce")
     */
    public function index(CategorieRepository $categorieRepository, AnnonceRepository $annonceRepository): Response
    {
        
        
        return $this->render('annonce/index.html.twig');
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
