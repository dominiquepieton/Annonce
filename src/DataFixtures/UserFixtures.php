<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbuser = 1; $nbuser <= 30; $nbuser++){
            $user = new User();

            $user->setEmail($faker->email);
            
            // Le premier user est admin et les autres users
            if($nbuser === 1){
                $user->setRoles(['ROLE_ADMIN']);
                $user->setPassword($this->encoder->encodePassword($user, 'DOm091977!'));
            }else{
                $user->setRoles(['ROLE_USER']);
                $user->setPassword($this->encoder->encodePassword($user, 'Oceane1940'));
            }
            
            $user->setName($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setIsVerified($faker->numberBetween(0, 1));

            $manager->persist($user);

            // Enregistrer la catégorie dans une reference afin de pouvoir la passer au autre fixture..
            $this->addReference('user_'.$nbuser, $user);
        }

        $manager->flush();
    }

    
}