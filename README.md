# Annonce

Créer avec symfony 5. Il est sur le principe d'un site d'annonce.

## Extension utiliser :

J'ai utlisé le bundle CKEditorBundle pour avoir dans le formulaire de création d'annonce, un éditeur de texte. Avec lequel, j'ai intégré le bundle ElfinderBundle afin
de pouvoir ajouter des images.

    - CKEditorBundle vient de FriendsofSymfony : https://github.com/FriendsOfSymfony/FOSCKEditorBundle.
    - FMElfinderBundle vient de Helios-ag : https://github.com/helios-ag/FMElfinderBundle.
    
## Service FileUploader :

Cela permet de séparer la gestion du traitement d'upload de fichier dans le controleur ainsi de rendre le code plus lisible.

    * Utilisation dans le fichier de création de formulaire :
        Ajouter le code suivant et n'oublié pas de faire l'importation de FileType (use Symfony\Component\Form\Extension\Core\Type\FileType;)
        et de File (use Symfony\Component\Validator\Constraints\File;) pour pouvoir gérer les types mime de fichier autorisés, le message d'erreur,
        mais vous pouvez rajouter la taille maximun ('maxSize' => '1024k')
        
        ->add('upload_file', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([ 
                        'mimeTypes' => [ 
                            'image/gif', 
                            'image/jpeg', 
                            'image/png', 
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => "This document isn't valid.",
                    ])
                ],
            ])
            
    * Utilisation dans le controleur :
    Après avoir vérifié le formulaire, il faut vérifier si on a une valeur dans le champ 'upload_file' et si c'est le cas nous pourrons faire le traitement suivant:
    
    public function createAnnonce(Request $request, FileUploader $fileUploader): Response
    {
        //............ Partie création du formulaire

        if($form->isSubmitted() && $form->isValid()){
            
            $file = $form->get('upload_file')->getData(); // Récupération de la donnée du champ upload
            // Vérification de la donnée si vide ou pas
            if($file) { 
                $fileName = $fileUploader->upload($file); // Traitement du transfert du fichier dans le dossier uploads
                $annonce->setFileName($fileName); // Ajout de la valeur grace au setter de l'entité avant de le persister
            }
        //............... Partie du traitement persister les données et envois à la base de données puis redirection

        }
        //............. Partie pour l'affichage du formulaire
    }   
