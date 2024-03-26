<?php

namespace App\DataFixtures;

use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FilmFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //initialiser Faker
        $faker = Factory ::create("fr_FR");
        $faker -> addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
        foreach (range(0, 25) as $i) {
            $newFilm = new Film();
            $newFilm -> setTitre($faker -> movie);
            $newFilm -> setDuree(random_int(90, 200));
            $manager -> persist($newFilm);
        }
        $manager -> flush();
    }
}


