<?php

namespace App\Form;

use App\Entity\Mail;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre du mail :'])
            ->add('name', TextType::class, ['label' => 'Nom du destinataire'])
            ->add('firstname', TextType::class, ['label' => 'Prénom du destinataire'])
            ->add('emailcontact', EmailType::class, ['label' => 'Email du destinataire'])
            ->add('emailexpediteur', EmailType::class, ['label' => 'Email expediteur'])
            ->add('content', CKEditorType::class, ['label' => 'Message'])
            ->add('Envoyer', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mail::class,
        ]);
    }
}
