<?php

namespace App\Repository;

use App\Entity\EIDPatient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EIDPatient|null find($id, $lockMode = null, $lockVersion = null)
 * @method EIDPatient|null findOneBy(array $criteria, array $orderBy = null)
 * @method EIDPatient[]    findAll()
 * @method EIDPatient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EIDPatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EIDPatient::class);
    }

    // /**
    //  * @return EIDPatient[] Returns an array of EIDPatient objects
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
    public function findOneBySomeField($value): ?EIDPatient
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
