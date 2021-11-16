<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AnnonceRepository $annonceRepo): Response
    { 
        $annonce = $annonceRepo->findBy(['categorie' => 2]);
        $vehicules = $annonceRepo->findBy(['categorie' => 3]);

        return $this->render('home.html.twig', ['annonce' => $annonce, 'vehicules' => $vehicules]);
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
