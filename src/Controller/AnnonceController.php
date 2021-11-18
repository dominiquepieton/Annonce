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
}
