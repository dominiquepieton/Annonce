<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            1 => ['name' => 'Immobilier'],
            2 => ['name' => 'Console'],
            3 => ['name' => 'Ordinateur'],
            4 => ['name' => 'Vehicule'],
            5 => ['name' => 'Mobilier interieur'],
            6 => ['name' => 'Mobilier exterieur'],
            7 => ['name' => 'Outillage']
        ];

        foreach($categories as $key => $value){
            $categorie = new Categorie();
            $categorie->setName($value['name']);
            $manager->persist($categorie);

            // Enregistrer la catégorie dans une reference afin de pouvoir la passer au autre fixture..
            $this->addReference('categorie_'.$key, $categorie);
        }

        // auto sans donnee fixe
        

        $manager->flush();
    }
}