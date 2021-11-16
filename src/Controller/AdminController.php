<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Contact;
use App\Entity\Categorie;
use App\Form\AnnonceType;
use App\Form\CategorieType;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use App\Repository\ContactRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Controller permettant d'administrer le site
 * 
 * Route par defaut 
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * permet afficher les données du site de façon globale
     * @Route("/dashboard", name="dashboard")
     */
    public function index(UserRepository $userRepo, AnnonceRepository $annonceRepo, CategorieRepository $categorieRepo, ContactRepository $contactRepo): Response
    {
        
        $users = $userRepo->findAll();
        $annonces = $annonceRepo->findAll();
        $categories = $categorieRepo->findAll();
        $contacts = $contactRepo->findAll();
         
        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'annonces' => $annonces,
            'categories' => $categories,
            'contacts' => $contacts
        ]);
    }


    /**
     * Permet de voir toutes les catégories et de pouvoir les gérer
     * @Route("/categorie", name="categorie")
     *
     * @param CategorieRepository $categorieRepo
     * @return void
     */
    public function allCategorie(CategorieRepository $categorieRepo)
    {
        $categories = $categorieRepo->findAll();

        return $this->render('admin/categorie/indexCategorie.html.twig', ['categories' => $categories]);
    }


    /**
     * @Route("/categorie/create", name="categorie_create")
     */
    public function createCategorie(Request $request): Response
    {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a été enregistré.');

        }

        return $this->render('admin/categorie/createCategorie.html.twig', ['form' => $form->createView()]);
    }


    /**
     * Modification d'une categorie
     * @Route("/categorie/edit/{id}", name="categorie_edit")
     */
    public function editCategorie($id, CategorieRepository $categorieRepo,Request $request): Response
    {
        $categorie = $categorieRepo->find($id);

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a bien été modifier.');

            return $this->redirectToRoute('admin_dashboard');

        }

        return $this->render('admin/categorie/editCategorie.html.twig', ['form' => $form->createView()]);
    }


    /**
     * Permet de supprimer une catégorie
     * @Route("/categorie/delete/{id}", name="categorie_delete")
     * 
     * @param Categorie $categorie
     * @return void
     */
    public function deleteCategorie(Categorie $categorie)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        $this->addFlash('message', 'La catégorie a étè supprimée avec succes...');

        return $this->redirectToRoute('admin_categorie');
    }


    /**
     * Permet d'afficher tous les users et de gerer la gestion des users
     * @Route("/users", name="users")
     * 
     * @param UserRepository $userRepo
     * @return void
     */
    public function indexUser(UserRepository $userRepo)
    {
        $users = $userRepo->findAll();

        return $this->render('admin/user/indexUser.html.twig', ['users' => $users]);
    }


    /**
     * Permet d'afficher toutes les annonces et de les gérer
     *
     * @Route("/annonce", name="annonce")
     * @param AnnonceRepository $annonceRepo
     * @return void
     */
    public function allAnnonce(AnnonceRepository $annonceRepo)
    {
        $annonces = $annonceRepo->findAll();

        return $this->render('admin/annonce/indexAnnonce.html.twig', ['annonces' => $annonces]);
    }



    /**
     * Permet d'editer une annonce
     * @Route("/annonce/edit/{id}", name="annonce_edit")
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

            $this->addFlash('success', 'La modification de cette annonce a bien été enregistré.');

            return $this->redirectToRoute('admin_annonce');
        } 

        return $this->render('admin/annonce/editAnnonce.html.twig', ['form' => $form->createView()]);
    }


    /**
     * Permet la suppression d'une annonce
     * @Route("/annonce/delete/{id}", name="annonce_delete")
     * @param Annonce $annonce
     * @return void
     */
    public function deleteAnnonce(Annonce $annonce)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($annonce);
        $entityManager->flush();

        $this->addFlash('success', 'La suppression de cette annonce a bien été enregistré.');

        return $this->redirectToRoute('admin_annonce');
    }


    /**
     * Permet la suppression d'une annonce
     * @Route("/annonce/validate/{id}", name="annonce_validate")
     * @param Annonce $annonce
     * @return void
     */
    public function validateAnnonce(Annonce $annonce)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if($annonce->getActive() === false){
            $annonce->setActive(1);

            $entityManager->persist($annonce);
            $entityManager->flush();

            $this->addFlash('success', "L'annonce est maintenant publié...");

            return $this->redirectToRoute('admin_annonce');
        }else{
            $annonce->setActive(0);

            $entityManager->persist($annonce);
            $entityManager->flush();

            $this->addFlash('success', "L'annonce est passé à non-publié..");

            return $this->redirectToRoute('admin_annonce'); 
        }   
    }

    /**
     * Permet de visualiser tous les message reçu
     *
     * @Route("/contact", name="contact")
     * @param ContactRepository $contactRepository
     * @return void
     */
    public function allContact(ContactRepository $contactRepository)
    {
        $contacts = $contactRepository->findAll();
         
        return $this->render('admin/contact/index.html.twig', ['contacts' => $contacts]);
    }


    /**
     * Permet d'envoyer un mail pour la reponse au message
     * 
     * @Route("/contact/{id}", name="contact_send")
     * @param [type] $id
     * @param Contact $contact
     * @return void
     */
    public function sendContact($id, Contact $contact)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if($contact->getValidate() == false){
            $contact->setValidate(1);

            $entityManager->persist($contact);
            $entityManager->flush();

            //$this->addFlash('success', "L'annonce est maintenant publié...");

            return $this->redirectToRoute('admin_contact');

        }else{
            $contact->setValidate(0);

            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('admin_contact');
        }

        
    }
}
