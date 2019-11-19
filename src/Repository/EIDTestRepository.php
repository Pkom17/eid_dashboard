<?php

namespace App\Repository;

use App\Entity\EIDTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EIDTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method EIDTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method EIDTest[]    findAll()
 * @method EIDTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EIDTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EIDTest::class);
    }

    // /**
    //  * @return EIDTest[] Returns an array of EIDTest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EIDTest
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
