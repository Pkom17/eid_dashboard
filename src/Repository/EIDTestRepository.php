<?php

namespace App\Repository;

use App\Entity\EIDTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EIDTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method EIDTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method EIDTest[]    findAll()
 * @method EIDTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EIDTestRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, EIDTest::class);
    }

// /**
//  * @return EIDTest[] Returns an array of EIDTest objects
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
      public function findOneBySomeField($value): ?EIDTest
      {
      return $this->createQueryBuilder('e')
      ->andWhere('e.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */

    public function addEIDTest(?EIDTest $eidTest) {
        $conn = $this->getEntityManager()->getConnection();
        $query = "insert into eid_test (infant_gender, which_pcr, second_pcr_test_reason, rejected_reason, pcr_result, patient_id, site_id, district_id, region_id, partner_id, labno, collected_date, received_date, completed_date, released_date, dispatched_date, infant_age_month, infant_age_week, date_updated, plateforme_id, yearmonth)"
                . " values(:infant_gender,:which_pcr,:second_pcr_test_reason,:rejected_reason,:pcr_result,:patient_id,:site_id,:district_id,:region_id,:partner_id,:labno,:collected_date,"
                . " :received_date,:completed_date,:released_date,:dispatched_date,:infant_age_month,:infant_age_week,:date_updated,:plateforme_id,:yearmonth)";
        $prep = $conn->prepare($query);
        $prep->execute(
                [
                    'infant_gender' => $eidTest->getInfantGender(),
                    'which_pcr' => $eidTest->getWhichPCR(),
                    'second_pcr_test_reason' => $eidTest->getSecondPCRTestReason(),
                    'rejected_reason' => $eidTest->getRejectedReason(),
                    'pcr_result' => $eidTest->getPcrResult(),
                    'patient_id' => $eidTest->getPatient()->getId(),
                    'site_id' => $eidTest->getSite(),
                    'district_id' => $eidTest->getDistrict(),
                    'region_id' => $eidTest->getRegion(),
                    'partner_id' => $eidTest->getPartner(),
                    'labno' => $eidTest->getLabno(),
                    'collected_date' => $eidTest->getCollectedDate()->format('Y-m-d H:i'),
                    'received_date' => $eidTest->getReceivedDate()->format('Y-m-d H:i'),
                    'completed_date' => $eidTest->getCompletedDate()->format('Y-m-d H:i'),
                    'released_date' => $eidTest->getReceivedDate()->format('Y-m-d H:i'),
                    'dispatched_date' => $eidTest->getDispatchedDate()->format('Y-m-d H:i'),
                    'infant_age_month' => $eidTest->getInfantAgeMonth(),
                    'infant_age_week' => $eidTest->getInfantAgeWeek(),
                    'date_updated' => $eidTest->getDateUpdated()->format('Y-m-d H:i'),
                    'plateforme_id' => $eidTest->getPlateforme(),
                    'yearmonth' => $eidTest->getYearmonth(),
        ]);
        $conn->close();
    }

    public function updateEIDTest(?EIDTest $eidTest) {
        $conn = $this->getEntityManager()->getConnection();
        $query = "update eid_test set infant_gender =:infant_gender, which_pcr =:which_pcr, second_pcr_test_reason=:second_pcr_test_reason,  rejected_reason=:rejected_reason,"
                . " pcr_result=:pcr_result,  patient_id=:patient_id, site_id=:site_id, district_id=:district_id, region_id = :region_id, partner_id=:partner_id, "
                . "labno=:labno, collected_date=:collected_date, received_date=:received_date, completed_date=:completed_date, released_date=:released_date,"
                . " dispatched_date=:dispatched_date, infant_age_month=:infant_age_month,infant_age_week=:infant_age_week, date_updated=:date_updated, "
                . "plateforme_id=:plateforme_id, yearmonth=:yearmonth where id = :id";
        $prep = $conn->prepare($query);
        $prep->execute(
                [
                    'infant_gender' => $eidTest->getInfantGender(),
                    'which_pcr' => $eidTest->getWhichPCR(),
                    'second_pcr_test_reason' => $eidTest->getSecondPCRTestReason(),
                    'rejected_reason' => $eidTest->getRejectedReason(),
                    'pcr_result' => $eidTest->getPcrResult(),
                    'patient_id' => $eidTest->getPatient()->getId(),
                    'site_id' => $eidTest->getSite(),
                    'district_id' => $eidTest->getDistrict(),
                    'region_id' => $eidTest->getRegion(),
                    'partner_id' => $eidTest->getPartner(),
                    'labno' => $eidTest->getLabno(),
                    'collected_date' => $eidTest->getCollectedDate()->format('Y-m-d H:i'),
                    'received_date' => $eidTest->getReceivedDate()->format('Y-m-d H:i'),
                    'completed_date' => $eidTest->getCompletedDate()->format('Y-m-d H:i'),
                    'released_date' => $eidTest->getReceivedDate()->format('Y-m-d H:i'),
                    'dispatched_date' => $eidTest->getDispatchedDate()->format('Y-m-d H:i'),
                    'infant_age_month' => $eidTest->getInfantAgeMonth(),
                    'infant_age_week' => $eidTest->getInfantAgeWeek(),
                    'date_updated' => $eidTest->getDateUpdated()->format('Y-m-d H:i'),
                    'plateforme_id' => $eidTest->getPlateforme(),
                    'yearmonth' => $eidTest->getYearmonth(),
                    'id' => $eidTest->getId(),
        ]);
        $conn->close();
    }

    public function getEidOutcomes($which_pcr, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes (:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'which_pcr' => $which_pcr,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEidOutcomesDetails($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "SELECT which_pcr, sum(pcr_result='Positif') as positif,sum(pcr_result='Négatif') as negatif FROM `eid_test` WHERE yearmonth between :from and :to group by which_pcr";
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

    public function getEidOutcomesByClinicType($which_pcr, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes_type_clinic (:region_id,:district_id,:site_id,:partner_id,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => 0,
                    'district_id' => 0,
                    'site_id' => 0,
                    'partner_id' => 0,
                    'which_pcr' => $which_pcr,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEidOutcomesByMotherRegimen($which_pcr, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes_mother_regimen (:region_id,:district_id,:site_id,:partner_id,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => 0,
                    'district_id' => 0,
                    'site_id' => 0,
                    'partner_id' => 0,
                    'which_pcr' => $which_pcr,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEidOutcomesByInfantARV($which_pcr, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes_infant_arv (:region_id,:district_id,:site_id,:partner_id,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => 0,
                    'district_id' => 0,
                    'site_id' => 0,
                    'partner_id' => 0,
                    'which_pcr' => $which_pcr,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEidOutcomesByMotherStatus($which_pcr, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes_hiv_status (:region_id,:district_id,:site_id,:partner_id,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => 0,
                    'district_id' => 0,
                    'site_id' => 0,
                    'partner_id' => 0,
                    'which_pcr' => $which_pcr,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEidOutcomesByRegion($which_pcr, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes_regions (:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'which_pcr' => $which_pcr,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEIDTestingTrends($which_pcr, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_testing_trends (:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'which_pcr' => $which_pcr,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEIDTrendsByYear($region_id, $district_id, $site_id, $age_month_min, $age_month_max, $which_pcr, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_trends_year (:region_id,:district_id,:site_id,:age_month_min,:age_month_max,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => $region_id,
                    'district_id' => $district_id,
                    'site_id' => $site_id,
                    'age_month_min' => $age_month_min,
                    'age_month_max' => $age_month_max,
                    'which_pcr' => $which_pcr,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEIDTrendsByQuarter($region_id, $district_id, $site_id, $age_month_min, $age_month_max, $which_pcr, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_trends_quarter (:region_id,:district_id,:site_id,:age_month_min,:age_month_max,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => $region_id,
                    'district_id' => $district_id,
                    'site_id' => $site_id,
                    'age_month_min' => $age_month_min,
                    'age_month_max' => $age_month_max,
                    'which_pcr' => $which_pcr,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEIDTrendsByMonth($region_id, $district_id, $site_id, $age_month_min, $age_month_max, $which_pcr, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_trends_month (:region_id,:district_id,:site_id,:partner_id,:age_month_min,:age_month_max,:which_pcr,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'region_id' => $region_id,
                    'district_id' => $district_id,
                    'site_id' => $site_id,
                    'partner_id' => 0,
                    'age_month_min' => $age_month_min,
                    'age_month_max' => $age_month_max,
                    'which_pcr' => $which_pcr,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getEIDTestSummary($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_summary_all_tests (:from,:to,@all_test,@pcr1,@pcr2) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $query2 = "select @all_test as all_test,@pcr1 as pcr1,@pcr2 as pcr2";
            $resutSet = $conn->query($query2);
            if ($resutSet) {
                $results = $resutSet->fetchAll(\PDO::FETCH_ASSOC);
            }
        }
        $conn->close();
        return $results;
    }

    public function getEIDTotalPatient($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select count(distinct(eid_patient.id)) as patient from eid_patient  join eid_test on eid_test.patient_id = eid_patient.id where yearmonth between :from and :to ; ";
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

    public function getEIDPositivity($from, $to) { //based on PCR 1
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select  pcr_result as result, count(distinct(eid_patient.id)) as nb from eid_patient  join eid_test on eid_test.patient_id = eid_patient.id where yearmonth between :from and :to and which_pcr =1 group by pcr_result";
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

    public function getTAT1($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select datediff(received_date,collected_date) as tat1 from eid_test where yearmonth between :from and :to order by tat1";
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

    public function getTAT2($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select datediff(completed_date,received_date) as tat2 from eid_test where yearmonth between :from and :to order by tat2";
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

    public function getTAT3($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select datediff(released_date,completed_date) as tat3 from eid_test where yearmonth between :from  and :to order by tat3 ";
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

}
