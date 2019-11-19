<?php

namespace App\Repository;

use App\Entity\SiteContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SiteContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method SiteContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method SiteContact[]    findAll()
 * @method SiteContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiteContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SiteContact::class);
    }

    // /**
    //  * @return SiteContact[] Returns an array of SiteContact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SiteContact
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
