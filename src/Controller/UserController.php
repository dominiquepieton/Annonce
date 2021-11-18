<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Form\EditProfileType;
use App\Repository\AnnonceRepository;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * Controlleur de l'utilisateur permettant de :
 *  - Voir son profil,
 *  - Editer son profil,
 *  - Modifier son password,
 *  - Créer une annonce, la modifier, la supprimer...
 * 
 * Route par defaut pour user
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {
        return $this->render('user/profile.html.twig');
    }


    /**
     * Permet d'editer le profile
     * @Route("/profile/edit", name="edit_profile")
     *
     */
    public function editProfile(Request $request)
    {
        $user = $this->getUser();
        
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre modification de profil a bien été enregistré.');

            return $this->redirectToRoute('user_profile');
        } 

        return $this->render('user/profile/editProfile.html.twig', ['form' => $form->createView()]);
    }


    /**
     * Permet la modification du password
     * @Route("/password/edit", name="edit_password")
     *
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $userPassword)
    {
       
        if($request->isMethod('POST')){
            
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getUser();

            if($request->request->get('newPassword') == $request->request->get('confirmPassword'))
            {
               $user->setPassword($userPassword->encodePassword($user, $request->request->get('confirmPassword')));
               $entityManager->flush();
               
               $this->addFlash('message', 'Votre password a été modifier avec succés.');
               
               return $this->redirectToRoute('user_profile');
               
            }else{
                $this->addFlash('error', 'Les deux password doivent être identiques');
            }

        }

        return $this->render('user/profile/editPassword.html.twig');
    }


    /**
     * Création d'une annonce
     * @Route("/annonce/create", name="annonce_create")
     */
    public function createAnnonce(Request $request, FileUploader $fileUploader): Response
    {
        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // nom du fichier
            $file = $form->get('upload_file')->getData();
            if($file) {
                $fileName = $fileUploader->upload($file);
                $annonce->setFileName($fileName);
            }
            // nom utilisateur connecté
            $annonce->setUser($this->getUser());
            // On met le boolean à false par défaut
            $annonce->setActive(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            $this->addFlash('success', 'Votre annonce a bien été enregistré.');

            return $this->redirectToRoute('home');

        }

        return $this->render('user/annonce/createAnnonce.html.twig', ['form' => $form->createView()]);
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


        return $this->render('user/annonce/readAnnonce.html.twig', ['ad' => $ad]);
    }




    /**
     * Permet d'editer une annonce
     * @Route("/annonce/edit/{id}", name="edit_annonce")
     *
     */
    public function editAnnonce($id, Request $request, AnnonceRepository $annonceRepo)
    {
        
        $annonce = $annonceRepo->find($id);
        
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            $this->addFlash('success', 'Votre modification de votre annonce a bien été enregistré.');

            return $this->redirectToRoute('user_profile');
        } 

        return $this->render('user/annonce/editAnnonce.html.twig', ['form' => $form->createView()]);
    }



    /**
     * Permet de supprimer une annonce
     * @Route("/annonce/delete/{id}", name="annonce_delete")
     * 
     * @param Annonce $annonce
     * @return void
     */
    public function deleteAnnonce(Annonce $annonce)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();

        $this->addFlash('message', 'Votre annonce a étè supprimée avec succes...');

        return $this->redirectToRoute('user_profile');
    }

    
}
