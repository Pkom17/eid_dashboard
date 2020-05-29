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
class EIDDictionaryRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
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

    public function findDictionaryInfo() {
        $results = [];
        $sql = 'select id,domain_code,entry_code from eid_dictionary';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $exec = $stmt->execute();
        if ($exec) {
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getTypeOfClinic() {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select id,entry_name from eid_dictionary where domain_code = :d";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'd' => EIDDictionary::TYPE_OF_CLINIC,
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }
    public function getPCR2Reasons() {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select id,entry_name from eid_dictionary where domain_code = :d";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'd' => EIDDictionary::REASON_FOR_SECOND_PCR,
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getMotherRegimen() {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select id,entry_name from eid_dictionary where domain_code = :d";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'd' => EIDDictionary::EID_MOTHER_ARV,
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getInfantARV() {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select id,entry_name from eid_dictionary where domain_code = :d";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'd' => EIDDictionary::EID_INFANT_ARV,
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getMotherHIVStatus() {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = "select id,entry_name from eid_dictionary where domain_code = :d";
        $prep = $conn->prepare($query);
        $exec = $prep->execute(
                [
                    'd' => EIDDictionary::HIV_STATUS,
        ]);
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

}
