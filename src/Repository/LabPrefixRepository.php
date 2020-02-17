<?php

namespace App\Repository;

use App\Entity\LabPrefix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LabPrefix|null find($id, $lockMode = null, $lockVersion = null)
 * @method LabPrefix|null findOneBy(array $criteria, array $orderBy = null)
 * @method LabPrefix[]    findAll()
 * @method LabPrefix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LabPrefixRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, LabPrefix::class);
    }

    // /**
    //  * @return LabPrefix[] Returns an array of LabPrefix objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('l')
      ->andWhere('l.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('l.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?LabPrefix
      {
      return $this->createQueryBuilder('l')
      ->andWhere('l.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
    public function findLabs() {
        $sql = 'select id,eid_prefix,plateforme_id from lab_prefix';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $stmt->closeCursor();
        $conn->close();
        return $data;
    }

}
