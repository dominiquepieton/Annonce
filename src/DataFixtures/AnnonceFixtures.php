<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Annonce;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\CategorieFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnnonceFixtures extends Fixture implements DependentFixtureInterface
{
   
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbannonce = 1; $nbannonce <= 90; $nbannonce++){
            $user = $this->getReference('user_'.$faker->numberBetween(1, 30));
            $categorie = $this->getReference('categorie_'.$faker->numberBetween(1, 7));
            $annonce = new Annonce();

            $annonce->setUser($user);
            $annonce->setCategorie($categorie);
            $annonce->setTitle($faker->realText(25));
            $annonce->setContent($faker->realText(450));
            $annonce->setActive($faker->numberBetween(0, 1));
            $annonce->setFilename($faker->imageUrl($width = 300, $height = 150));

            // upload et gernere les images
            //for($image = 1; $image < 4; $image++){
                //$img = $faker->image('public/uploads/images/annonces');
                //$imageAnnonce = new Image();
                //$imageAnnonce->setName(str_replace('public/uploads/images/annonces', '', $img));

                //$annonce->addImage($imageAnnonce).
            //}


            $manager->persist($annonce);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategorieFixtures::class,
            UserFixtures::class,
        ];
    }
}