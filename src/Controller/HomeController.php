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
}
