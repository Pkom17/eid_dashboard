<?php

namespace App\Repository;

use App\Entity\EIDAgeCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EIDAgeCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method EIDAgeCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method EIDAgeCategory[]    findAll()
 * @method EIDAgeCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EIDAgeCategoryRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, EIDAgeCategory::class);
    }

    public function getAgesCategories() {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = " select name,age_min,age_max from eid_age_category";
        $prep = $conn->prepare($query);
        $exec = $prep->execute();
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }
    public function getAgesCategoriesNames() {
        $results = [];
        $conn = $this->getEntityManager()->getConnection();
        $query = " select id,name from eid_age_category";
        $prep = $conn->prepare($query);
        $exec = $prep->execute();
        if ($exec) {
            $results = $prep->fetchAll(\PDO::FETCH_ASSOC);
        }
        $conn->close();
        return $results;
    }

    public function getAgeCategoryLimit($id) {
        $result = false;
        $conn = $this->getEntityManager()->getConnection();
        $query = " select name,age_min,age_max from eid_age_category where id = :id";
        $prep = $conn->prepare($query);
        $exec = $prep->execute([
            'id' => $id,
        ]);
        if ($exec) {
            $result = $prep->fetch(\PDO::FETCH_ASSOC);
        }
        if ($result === false) {
            $result = [
            'name' => "",
            'age_min' => -1,
            'age_max' => -1,
            ];
        }
        $conn->close();
        return $result;
    }

    // /**
    //  * @return EIDAgeCategory[] Returns an array of EIDAgeCategory objects
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
      public function findOneBySomeField($value): ?EIDAgeCategory
      {
      return $this->createQueryBuilder('e')
      ->andWhere('e.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
