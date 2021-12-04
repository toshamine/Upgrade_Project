<?php

namespace App\Repository;

use App\Entity\Cheaters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cheaters|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cheaters|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cheaters[]    findAll()
 * @method Cheaters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheatersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cheaters::class);
    }

    // /**
    //  * @return Cheaters[] Returns an array of Cheaters objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cheaters
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
