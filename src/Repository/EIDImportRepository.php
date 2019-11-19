<?php

namespace App\Repository;

use App\Entity\EIDImport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EIDImport|null find($id, $lockMode = null, $lockVersion = null)
 * @method EIDImport|null findOneBy(array $criteria, array $orderBy = null)
 * @method EIDImport[]    findAll()
 * @method EIDImport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EIDImportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EIDImport::class);
    }

    // /**
    //  * @return EIDImport[] Returns an array of EIDImport objects
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
    public function findOneBySomeField($value): ?EIDImport
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
