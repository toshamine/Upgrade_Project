<?php

namespace App\Repository;

use App\Entity\Records;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Records|null find($id, $lockMode = null, $lockVersion = null)
 * @method Records|null findOneBy(array $criteria, array $orderBy = null)
 * @method Records[]    findAll()
 * @method Records[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecordsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Records::class);
    }

    /**
    //  * @return Records[] Returns an array of Records objects
    //  */

    public function findByOrder($user)
    {
        return $this->createQueryBuilder('r')
            ->where('r.User = :user')
            ->setParameter('user',$user)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Records
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
