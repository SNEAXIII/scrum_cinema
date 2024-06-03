<?php

const DB_HOST = "localhost:3306";
const DB_NAME = "db_cinema";
const DB_USER = "root";
const DB_PASSWORD = "root";
const DATE_FORMAT = "d/m/Y";

function createConnection(): PDO
{
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    try {
        $connection = new PDO($dsn, DB_USER, DB_PASSWORD);
        $connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    } catch (PDOException $erreur) {
        die("Error : " . $erreur -> getMessage());
    }
}

function getStatistique3MeilleursFilms(string $date1, string $date2): array
{
    $connexion = createConnection();
    $requeteSQL = "
select titre, sum(r.nombre_places) as nombre_entree
from reservation as r
inner join seance as s on r.seance_id = s.id
inner join film as f on s.film_id = f.id
where date_projection between :date1 and :date2
group by f.id, titre
order by nombre_entree desc
limit 3";
    $requete = $connexion -> prepare($requeteSQL);
    $requete -> bindValue(":date1", $date1);
    $requete -> bindValue(":date2", $date2);
    $requete -> execute();
    return $requete -> fetchAll(PDO::FETCH_ASSOC);
}

$date1 = $argv[1];
$date2 = $argv[2];


$films = getStatistique3MeilleursFilms($date1, $date2);

echo "\ntitre/nombre de places\n";

foreach ($films as $film) {
    echo $film ["titre"] . " | " . $film['nombre_entree'],"\n\n";
}