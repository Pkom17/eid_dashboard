<?php

namespace App\Repository;

use App\Entity\District;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method District|null find($id, $lockMode = null, $lockVersion = null)
 * @method District|null findOneBy(array $criteria, array $orderBy = null)
 * @method District[]    findAll()
 * @method District[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DistrictRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, District::class);
    }

    // /**
    //  * @return District[] Returns an array of District objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('d')
      ->andWhere('d.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('d.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?District
      {
      return $this->createQueryBuilder('d')
      ->andWhere('d.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
    public function findDistricts() {
        $sql = 'select id,name from district order by name';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $stmt->closeCursor();
        $conn->close();
        return $data;
    }

    public function findDistrictsByRegion($id) {
        $sql = 'select id,name from district where region_id = :region order by name ';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'region' =>$id
        ]);
        $data = $stmt->fetchAll();
        $stmt->closeCursor();
        $conn->close();
        return $data;
    }

}
