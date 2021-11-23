<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AnnonceRepository $annonceRepo , CategorieRepository $categorieRepo): Response
    { 
        $categories = $categorieRepo->findall();
        
        foreach($categories as $categorie){
            if($categorie->getName() === 'Console'){
                $annonce = $annonceRepo->findByCategorie(['categorie' => $categorie->getId()]);
            }
            if($categorie->getName() === 'Vehicule'){
                $vehicules = $annonceRepo->findByCategorie(['categorie' => $categorie->getId()]);
            }
            if($categorie->getName() === 'Immobilier'){
                $immobiliers = $annonceRepo->findByCategorie(['categorie' => $categorie->getId()]);
            }
            if($categorie->getName() === 'Outillage'){
                $outillages = $annonceRepo->findByCategorie(['categorie' => $categorie->getId()]);
            }

        };

        return $this->render('home.html.twig', [
            'annonce' => $annonce,
            'vehicules' => $vehicules,
            'immobiliers' => $immobiliers,
            'outillages' => $outillages
        ]);
    }


    
}
