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
            'region' => $id
        ]);
        $data = $stmt->fetchAll();
        $stmt->closeCursor();
        $conn->close();
        return $data;
    }

    public function getEidOutcomesByDistrict($from, $to, $region = 0) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes_district (:region,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region' => $region,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEidDistrictsStats($from, $to, $region = 0) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes_district(:region,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region' => $region,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getTestsTrendsDistrict($district_id, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_trends_month (:region_id,:district_id,:site_id,:age_month_min,:age_month_max,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => 0,
                    'district_id' => $district_id,
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

    public function getDistrictOutcomes($district_id, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_outcomes(:region_id,:district_id,:site_id,:partner_id,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => 0,
                    'district_id' => $district_id,
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

    public function getDistrictOutcomesDetails($district_id, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "SELECT which_pcr, sum(pcr_result='Positif') as positif,sum(pcr_result='Négatif') as negatif FROM `eid_test` WHERE yearmonth between :from and :to and district_id = :district_id group by which_pcr";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'district_id' => $district_id,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getDistrictOutcomesAge($district_id, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_org_outcomes_age(:region_id,:district_id,:site_id,:partner_id,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => 0,
                    'district_id' => $district_id,
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
