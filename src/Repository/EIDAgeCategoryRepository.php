<?php

namespace App\Repository;

use App\Entity\EIDAgeCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EIDAgeCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method EIDAgeCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method EIDAgeCategory[]    findAll()
 * @method EIDAgeCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EIDAgeCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EIDAgeCategory::class);
    }

    // /**
    //  * @return EIDAgeCategory[] Returns an array of EIDAgeCategory objects
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
    public function findOneBySomeField($value): ?EIDAgeCategory
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
