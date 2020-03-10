<?php

namespace App\Repository;

use App\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Site|null find($id, $lockMode = null, $lockVersion = null)
 * @method Site|null findOneBy(array $criteria, array $orderBy = null)
 * @method Site[]    findAll()
 * @method Site[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiteRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Site::class);
    }

    // /**
    //  * @return Site[] Returns an array of Site objects
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
      public function findOneBySomeField($value): ?Site
      {
      return $this->createQueryBuilder('s')
      ->andWhere('s.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */

    public function findSites() {
        $sql = 'select id,datim_code,district_id,region_id,partner_id from site';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $stmt->closeCursor();
        $conn->close();
        return $data;
    }

    public function findSitesByDistrict($id) {
        $sql = 'select id,name from site where district_id = :district order by name ';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'district' =>$id,
        ]);
        $data = $stmt->fetchAll();
        $stmt->closeCursor();
        $conn->close();
        return $data;
    }

}
