<?php

namespace App\Services;

class FilmUtils
{
    private array $intToMonthArray;


    public function __construct()
    {
        $this -> intToMonthArray = [
            1 => "Janvier",
            2 => "Février",
            3 => "Mars",
            4 => "Avril",
            5 => "Mai",
            6 => "Juin",
            7 => "Juillet",
            8 => "Août",
            9 => "Septembre",
            10 => "Octobre",
            11 => "Novembre",
            12 => "Décembre"
        ];
    }

    public function convertDateAndIntToStringForAFilm(array $film): array
    {
//        todo supprimer les dates antidatées
        foreach ($film["seances"] as &$seance) {
            $date = date_create_from_format("Y-m-d\TH:i:sP", $seance["dateProjection"]);
            $seance["dateProjection"] = $date;
        }
        sort($film["seances"]);
        foreach ($film["seances"] as &$seance) {
            $date = $seance["dateProjection"];
            $year = $date -> format("Y");
            $month = $this -> intToMonthArray[intval($date -> format("m"))];
            $day = $date -> format("d");
            $hour = $date -> format("H");
            $minute = $date -> format("i");
            $seance["dateProjection"] = "$day $month $year, à $hour h $minute";
        }
        $film["duree"] = $this -> getMinuteToHourMinute($film["duree"]);
        return $film;
    }

    public function getMinuteToHourMinute(int $minutes): string
    {
        $intHour = floor($minutes / 60);
        $intMinutes = $minutes % 60;
        if ($intHour > 0) {
            return "$intHour h $intMinutes m";
        }
        return "$intMinutes m";
    }

    public function convertFilmsIntToMinute(array $films): array
    {
        foreach ($films as &$film) {
            $date = $this -> getMinuteToHourMinute($film["duree"]);
            $film["duree"] = $date;
        }
        return $films;
    }
}