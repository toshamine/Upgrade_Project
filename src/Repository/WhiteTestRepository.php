<?php

namespace App\Repository;

use App\Entity\WhiteTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WhiteTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method WhiteTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method WhiteTest[]    findAll()
 * @method WhiteTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WhiteTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WhiteTest::class);
    }

    // /**
    //  * @return WhiteTest[] Returns an array of WhiteTest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WhiteTest
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
