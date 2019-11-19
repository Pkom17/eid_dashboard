<?php

namespace App\Repository;

use App\Entity\EIDDictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EIDDictionary|null find($id, $lockMode = null, $lockVersion = null)
 * @method EIDDictionary|null findOneBy(array $criteria, array $orderBy = null)
 * @method EIDDictionary[]    findAll()
 * @method EIDDictionary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EIDDictionaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EIDDictionary::class);
    }

    // /**
    //  * @return EIDDictionary[] Returns an array of EIDDictionary objects
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
    public function findOneBySomeField($value): ?EIDDictionary
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
