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
    public function getEidOutcomesLabsAge($which_pcr, $from, $to) {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "CALL proc_get_eid_labs_age(:which_pcr,:from,:to) ";
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

}
