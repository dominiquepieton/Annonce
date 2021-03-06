<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UploadFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('upload_file', FileType::class, [
                'label' => false,
                'mapped' => false, // Tell that there is no Entity to link
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
            ->add('send', SubmitType::class);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}