<?php

namespace App\Repository;

use App\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Region|null find($id, $lockMode = null, $lockVersion = null)
 * @method Region|null findOneBy(array $criteria, array $orderBy = null)
 * @method Region[]    findAll()
 * @method Region[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegionRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Region::class);
    }

    // /**
    //  * @return Region[] Returns an array of Region objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('r')
      ->andWhere('r.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('r.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Region
      {
      return $this->createQueryBuilder('r')
      ->andWhere('r.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */

    public function findRegions() {
        $sql = 'select id,name from region order by name';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $stmt->closeCursor();
        $conn->close();
        return $data;
    }

    public function getEidRegionsStats($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes_region(:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getTestsTrendsRegion($region_id, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_trends_month (:region_id,:district_id,:site_id,:age_month_min,:age_month_max,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => $region_id,
                    'district_id' => 0, // no distric
                    'site_id' => 0, // no site
                    'age_month_min' => -1, // no age
                    'age_month_max' => -1, //no age
                    'which_pcr' => 0,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getRegionOutcomes($region_id, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_outcomes(:region_id,:district_id,:site_id,:partner_id,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => $region_id,
                    'district_id' => 0,
                    'site_id' => 0,
                    'partner_id' => 0,
                    'which_pcr' => 0,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getRegionOutcomesDetails($region_id,$from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "SELECT which_pcr, sum(pcr_result='Positif') as positif,sum(pcr_result='Négatif') as negatif FROM `eid_test` WHERE yearmonth between :from and :to and region_id = :region_id group by which_pcr";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => $region_id,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

        public function getRegionOutcomesAge($region_id, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_org_outcomes_age(:region_id,:district_id,:site_id,:partner_id,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => $region_id,
                    'district_id' => 0,
                    'site_id' => 0,
                    'partner_id' => 0,
                    'which_pcr' => 0,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }
}
