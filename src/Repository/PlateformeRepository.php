<?php

namespace App\Repository;

use App\Entity\Plateforme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Plateforme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plateforme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plateforme[]    findAll()
 * @method Plateforme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlateformeRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Plateforme::class);
    }

    // /**
    //  * @return Plateforme[] Returns an array of Plateforme objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('p')
      ->andWhere('p.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('p.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Plateforme
      {
      return $this->createQueryBuilder('p')
      ->andWhere('p.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */

    public function getEidOutcomesLabsStats($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_outcomes_plateforme (:from,:to) ";
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

    public function getEidOutcomesLabsAge($which_pcr, $lab, $age_month_min, $age_month_max, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_labs_age(:which_pcr,:lab,:age_month_min,:age_month_max,:from,:to) ";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'lab' => $lab,
                    'which_pcr' => $which_pcr,
                    'age_month_min' => $age_month_min,
                    'age_month_max' => $age_month_max,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function findPlateformes() {
        $sql = 'select id,name from plateforme order by name';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $conn->close();
        return $data;
    }

    public function findEIDPlateformes() {
        $sql = 'select id,name from plateforme  where eid_active = 1 order by name';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $conn->close();
        return $data;
    }

    public function getPlateformeTATs($plateforme,$from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select  YEAR(released_date) as year,MONTH(released_date) as month,datediff(received_date,collected_date) as tat1, datediff(completed_date,received_date) as tat2, datediff(released_date,completed_date) as tat3  from eid_test where yearmonth between :from and :to and plateforme_id = :plateforme order by year,month";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'plateforme' => $plateforme,
                    'from' => $from,
                    'to' => $to
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }
    public function getAllPlateformesTATs($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select p.name plateforme, datediff(received_date,collected_date) as tat1, datediff(completed_date,received_date) as tat2, datediff(released_date,completed_date) as tat3  from eid_test et join plateforme p on et.plateforme_id = p.id where yearmonth between :from and :to order by plateforme";
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
    public function getPlateformeTAT1($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select  YEAR(released_date) as year,MONTH(released_date) as month,p.name plateforme, datediff(received_date,collected_date) as tat1 from eid_test et join plateforme p on et.plateforme_id = p.id where yearmonth between :from and :to order by tat1";
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
    
        public function getPlateformeTAT2($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select  YEAR(released_date) as year,MONTH(released_date) as month,p.name plateforme, datediff(completed_date,received_date) as tat2 from eid_test et join plateforme p on et.plateforme_id = p.id where yearmonth between :from and :to order by tat2";
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
    
        public function getPlateformeTAT3($from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select  YEAR(released_date) as year,MONTH(released_date) as month,p.name plateforme, datediff(released_date,completed_date) as tat3 from eid_test et join plateforme p on et.plateforme_id = p.id where yearmonth between :from and :to order by tat3";
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
