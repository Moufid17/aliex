<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordHasherInterface $hasher */
    private $hasher;

    /** @var string $pwd */
    private $pwd = 'test';

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $adminNames=[
             'moufid','sonny','moustakhim','hala'
        ];
        foreach($adminNames as $name){
          $user = (new User())
            ->setEmail('dev.'.$name.'@gmail.com')
            ->setRoles(["ROLE_ADMIN"]);
          $user->setFirstname($name);            
          $user->setLastname($name);
          $user->setUsername($name.'@dev');
          $user->setPassword($this->hasher->hashPassword($user, $name.'@dev'));
          $manager->persist($user);
        };
        $user = (new User())
            ->setEmail('aliexesgi2022app@gmail.com')
            ->setRoles(["ROLE_SUPER_ADMIN"]);
            $user->setFirstname('aliexesgi');
            $user->setLastname('esgi');
            $user->setUsername('esgiadmin');
        $user->setPassword($this->hasher->hashPassword($user,'esgiadmin'));
        $manager->persist($user);

        for ($i = 0; $i < 10; $i++) {
            $user = (new User())
                ->setEmail($faker->email)
                ->setRoles([]);
                $user->setFirstname($faker->firstname);
                $user->setLastname($faker->lastname);
                $user->setUsername($faker->username);
            $user->setPassword($this->hasher->hashPassword($user, $this->pwd));
            $manager->persist($user);
        }

        $manager->flush();
    }
}