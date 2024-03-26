<?php

namespace App\DataFixtures;

use App\Entity\Salle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SalleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //initialiser Faker
        $faker = Factory ::create("fr_FR");
        foreach (range(0, 4) as $i) {
            $newSalle = new Salle();
            $newSalle->setNom($faker->name());
            $newSalle->setNombrePlaces($faker->numberBetween(5, 400));
            $manager -> persist($newSalle);
        }
        $manager -> flush();
    }
}


