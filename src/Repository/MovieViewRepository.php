<?php

namespace App\Repository;

use App\Entity\MovieView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MovieView|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieView|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieView[]    findAll()
 * @method MovieView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieViewRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MovieView::class);
    }

    // /**
    //  * @return MovieView[] Returns an array of MovieView objects
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
    public function findOneBySomeField($value): ?MovieView
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
