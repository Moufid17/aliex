<?php

namespace App\DataFixtures;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
    
         $faker = \Faker\Factory::create();
         for ($i = 0; $i < 10; $i++) {
              $category = new Category();
              $category->setName($faker->name);
              $manager->persist($category);

         }

        $manager->flush();
    }
}
