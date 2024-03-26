<?php

namespace App\Repository;

use App\Entity\Film;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Film>
 *
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent ::__construct($registry, Film::class);
    }

    /**
     * @return Film[] Returns an array of Film objects
     */
    public function findAllFilmsAffiche(): array
    {
        return $this -> createQueryBuilder('film')
            -> select('DISTINCT film')
            -> from('App\Entity\Film', 'f')
            -> innerJoin('App\Entity\Seance', 's', 'WITH', 's.film = film')
            -> andWhere('s.dateProjection  >= :now')
            -> setParameter('now', new DateTime())
            -> getQuery()
            -> getResult();
    }
}