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
        $sql = 'select id,name,datim_code,district_id,region_id,partner_id from site';
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
            'district' => $id,
        ]);
        $data = $stmt->fetchAll();
        $stmt->closeCursor();
        $conn->close();
        return $data;
    }

    public function getEidOutcomesBySite($from, $to, $district = 0) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes_site (:district,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'district' => $district,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEidSitesStats($from, $to, $district = 0) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes_site(:district,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'district' => $district,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getTestsTrendsSite($site_id, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_trends_month (:region_id,:district_id,:site_id,:age_month_min,:age_month_max,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => 0,
                    'district_id' => 0,
                    'site_id' => $site_id, // no site
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

    public function getSiteOutcomes($site_id, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_outcomes(:region_id,:district_id,:site_id,:partner_id,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => 0,
                    'district_id' => 0,
                    'site_id' => $site_id,
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

    public function getSiteOutcomesDetails($site_id, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "SELECT which_pcr, sum(pcr_result='Positif') as positif,sum(pcr_result='Négatif') as negatif FROM `eid_test` WHERE yearmonth between :from and :to and site_id = :site_id group by which_pcr";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'site_id' => $site_id,
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
