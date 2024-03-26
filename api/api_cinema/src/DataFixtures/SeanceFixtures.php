<?php

namespace App\DataFixtures;

use App\Entity\Seance;
use App\Repository\FilmRepository;
use App\Repository\SalleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeanceFixtures extends Fixture
{
    private FilmRepository $filmRepository;
    private SalleRepository $salleRepository;

    public function __construct(FilmRepository $filmRepository, SalleRepository $salleRepository)
    {
        $this -> filmRepository = $filmRepository;
        $this -> salleRepository = $salleRepository;
    }

    public function load(ObjectManager $manager): void
    {
        //initialiser Faker
        $faker = Factory ::create("fr_FR");
        $arrayFilms = $this -> filmRepository -> findAll();
        $arraySalles = $this -> salleRepository -> findAll();

        foreach (range(0, 75) as $i) {
            $seance = new Seance();
            if ($i % 3 == 0) {
                $startDate = "now";
                $endDate = "1 month";
            } else {
                $startDate = "-1 month";
                $endDate = "now";
            }
            $seance -> setDateProjection($faker -> dateTimeBetween($startDate, $endDate));
            if ($i % 4 == 3) {
                $decimal = 0.5;
            } else {
                $decimal = 0;
            }
            $tarifNormal = random_int(7, 15) + $decimal;
            $seance -> setTarifNormal($tarifNormal);
            $seance -> setTarifReduit($tarifNormal - 3);
            $seance -> setFilm($faker -> randomElement($arrayFilms));
            $seance -> setSalle($faker -> randomElement($arraySalles));
            $manager -> persist($seance);

        }
        $manager -> flush();
    }


}