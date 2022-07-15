<?php

namespace App\DataFixtures;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Category;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {     
        $faker = \Faker\Factory::create();
        $users = $manager->getRepository(User::class)->findAll();
        $categorys = $manager->getRepository(Category::class)->findAll();
        $weight = [12.13, 16.87, 11.50, 17.74];

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName('product '.$i);
            $product->setPrice(mt_rand(10, 100));
            $product->setWeight($faker->randomElement($weight));
            $product->setDescription($faker->realText(200));
            $product->setOwner($faker->randomElement($users));
            $product->setCategory($faker->randomElement($categorys));
            // $product->setImageName('localhost/images/products/'.'velo-ancien-62cc4454230d3513321004.jpg');
            $product->setImageName('default.jpg');
            $product->setImageSize('8194');
            $product->setUpdatedAt(new DateTime());
            $manager->persist($product);
        }
    

        $manager->flush();
    }
}
