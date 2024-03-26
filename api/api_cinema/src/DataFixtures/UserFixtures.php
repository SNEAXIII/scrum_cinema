<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        // Initialiser faker
        foreach(range(0,25) as $i) {
            $newUser = new User();
            $newUser->setEmail($faker->email());
            $hashedPassword = $this->hasher->hashPassword($newUser, "mdp");
            $newUser->setPassword($hashedPassword);
            $newUser->setRoles(['ROLE_USER']);
            $manager->persist($newUser);
        }

        $manager->flush();
    }
}
