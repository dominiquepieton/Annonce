<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminGraphController extends AbstractController
{

    /**
     * @Route("admin/graph/demo", name="graphdemo")
     */
    public function embedded(UserRepository $userRepository)
    {
        $user = [
            'active' => count($userRepository->findBy(['isVerified' => 1])),
            'non-active' => count($userRepository->findBy(['isVerified' => 0]))
        ];
        
        
        return $this->render('admin/graph/graph.html.twig', [
            'pie' => $user,
            'histogram' => $user,
            'curve' => $user,
        ]);
    }
}