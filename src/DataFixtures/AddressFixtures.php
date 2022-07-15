<?php

namespace App\DataFixtures;
use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {       
        $addressName = [
            'Maison','Travail'
        ];
         $faker = \Faker\Factory::create();
        $users = $manager->getRepository(User::class)->findAll();
        for ($i = 0; $i < 10; $i++) {

          $adress = new address();
          $adress->setName($faker->randomElement($addressName));
          $adress->setStreet($faker->streetname);
          $adress->setCity($faker->city);
          $adress->setCodepostal($faker->postcode);
          $adress->setPhone($faker->phoneNumber);
          $adress->setOwner($faker->randomElement($users));
          $adress->setCountry($faker->country);
          $adress->setCompany($faker->company); 
          $manager->persist($adress);                                  
        };
        $manager->flush();
    }
}
