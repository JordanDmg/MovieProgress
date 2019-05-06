<?php

namespace App\Repository;

use App\Entity\MovieView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Movie;
use App\Entity\User;

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

    
    public function findOneByUserAndMovie($userid, $movieid): ?MovieView
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

    public function findMovieByUserId($userId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT id_tmdb, name, poster_path
        FROM movie_view mv, movie m
        WHERE mv.user_id = :userId
        AND mv.movie_id=m.id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['userId' => $userId]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
    
}
