<?php

namespace App\Repository;

use App\Entity\MovieToWatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MovieToWatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieToWatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieToWatch[]    findAll()
 * @method MovieToWatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieToWatchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MovieToWatch::class);
    }

    // /**
    //  * @return MovieToWatch[] Returns an array of MovieToWatch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MovieToWatch
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findOneByUserAndMovie($userid, $movieid): ?MovieToWatch
    {
        return $this->createQueryBuilder('m')
            ->leftJoin("m.user", "u")
            ->leftJoin("m.movie", "mv")
            ->where('u.id = :user')
            ->andWhere("mv.id = :movie")
            ->setParameter('user', $userid)
            ->setParameter('movie', $movieid)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
