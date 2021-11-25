<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Form\MailType;
use App\Entity\Annonce;
use App\Entity\Contact;
use App\Entity\Categorie;
use App\Form\AnnonceType;
use App\Form\CategorieType;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use App\Repository\ContactRepository;
use App\Repository\CategorieRepository;
use App\Repository\MailRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(UserRepository $userRepo, AnnonceRepository $annonceRepo, CategorieRepository $categorieRepo, ContactRepository $contactRepo, MailRepository $mailRepository): Response
    {
        
        $users = $userRepo->lastFiveUser();
        $annonces = $annonceRepo->fiveAnnonce();
        $categories = $categorieRepo->lastFiveCategorie();
        $contacts = $contactRepo->lastFiveContact();
        $mails = $mailRepository->lastFiveMail();

        // Donnée du graphe
        $userData = count($userRepo->findBy(['isVerified' => 1]));
        $userNotValider = count($userRepo->findBy(['isVerified' => 0]));


        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'annonces' => $annonces,
            'categories' => $categories,
            'contacts' => $contacts,
            'mails' => $mails,
            'userData' => $userData,
            'userNotValider' => $userNotValider
        ]);
    }


    /**
     * Permet de voir toutes les catégories et de pouvoir les gérer
     * @Route("/categorie", name="categorie")
     *
     * @param CategorieRepository $categorieRepo
     * @return void
     */
    public function allCategorie(CategorieRepository $categorieRepo, PaginatorInterface $paginator, Request $request)
    {
        $datas = $categorieRepo->findAll();
        $categories = $paginator->paginate(
            $datas,
            $request->query->getInt('page', 1),
            5
        );
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
     * @Route("/users/", name="users")
     * 
     * @param UserRepository $userRepo
     * @return void
     */
    public function allUser(UserRepository $userRepo, PaginatorInterface $paginator, Request $request)
    {
        $data = $userRepo->findAll();
        // gestion de la pagination
        $users = $paginator->paginate(
            $data, // passe la requete contenant les datas
            $request->query->getInt('page', 1), // numéro de la page en cours
            10 // nbres de résultats par page
        );

        return $this->render('admin/user/indexUser.html.twig', ['users' => $users]);
    }

    /**
     * Création d'un graph pour le user
     * @Route("/users/graphic", name="userGraph")
     * @return void
     */
    public function graphUser(UserRepository $userRepo)
    {
        // Donnée du graphe
        $userData = count($userRepo->findBy(['isVerified' => 1]));
        $userNotValider = count($userRepo->findBy(['isVerified' => 0]));

        $user = [
            'Active' => [$userData],
            'Not active' => [$userNotValider]
        ];

        return $this->render('admin/graph/userGraph.html.twig', [
            'user' => $user
        ]);
    }


    /**
     * Permet d'afficher toutes les annonces et de les gérer
     *
     * @Route("/annonce", name="annonce")
     * @param AnnonceRepository $annonceRepo
     * @return void
     */
    public function allAnnonce(AnnonceRepository $annonceRepo, PaginatorInterface $paginator, Request $request)
    {
        $datas = $annonceRepo->findAll();
        $annonces = $paginator->paginate(
            $datas,
            $request->query->getInt('page', 1),
            10
        );
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
     * Permet la validation d'une annonce
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
    public function sendContact($id, Contact $contact, ContactRepository $contactRepository, Request $request, \Swift_Mailer $mailer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $mail = new Mail();
        $contacts = $contactRepository->findBy(['id' => $id]);

        $form = $this->createForm(MailType::class, $mail );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $email = $form->getData();
            
            // creation du mail
            $message = (new \Swift_Message($email->getTitle()))
                ->setFrom('contact@entrainement.test')
                ->setTo($email->getEmailContact())
                ->setBody(
                    $this->renderView(
                    'admin/emails/contact.html.twig', ['email' => $email]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            // gestion pour mettre la validate à true
            if($contact->getValidate() == false){
                $contact->setValidate(1);
    
                $entityManager->persist($contact);
                $entityManager->flush();
            }

            $entityManager->persist($mail);
            $entityManager->flush();

            $this->addFlash('success', 'Votre email a bien été envoyé....');

            return $this->redirectToRoute('admin_contact');
        }

        return $this->render('admin/emails/createMail.html.twig', [
            'form' => $form->createView(),
            'contacts' => $contacts
        ]);
    }

    /**
     * Permet la suppression d'un message
     *
     * @Route("/contact/delete/{id}", name="contact_delete")
     * @param Contact $contact
     * @return void
     */
    public function deleteContact(Contact $contact)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($contact);
        $entityManager->flush();

        return $this->redirectToRoute('admin_contact');
    }



    
}
