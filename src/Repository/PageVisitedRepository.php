<?php

namespace App\Repository;

use App\Entity\PageVisited;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PageVisited|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageVisited|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageVisited[]    findAll()
 * @method PageVisited[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageVisitedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageVisited::class);
    }

    // /**
    //  * @return PageVisited[] Returns an array of PageVisited objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PageVisited
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
