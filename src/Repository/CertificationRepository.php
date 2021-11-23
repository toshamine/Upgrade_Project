<?php

namespace App\Repository;

use App\Entity\Certification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Certification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Certification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Certification[]    findAll()
 * @method Certification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Certification::class);
    }

    // /**
    //  * @return Certification[] Returns an array of Certification objects
    //  */
    public function findByOrder()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findBycat($id)
    {
        return $this->createQueryBuilder('c')
            ->where("c.category = :id")
            ->setParameter("id",$id)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();

  }

    public function findByFilter($filter,$nofilter)
    {
        if($filter !=null) {
        return $this->createQueryBuilder('c')
            ->where("c.category IN :filter")
            ->setParameter("filter",$filter)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
        }


    }
    /*public function findByCategory($filters)
    {
        if($filters!=null){
        return $this->createQueryBuilder('c')
            ->where("c.category IN (:filters)")
            ->setParameter("filters",$filters)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;}
        else{
            return $this->createQueryBuilder('c')
                ->orderBy('c.id', 'DESC')
                ->getQuery()
                ->getResult()
                ;
        }
    }*/

    /*
    public function findOneBySomeField($value): ?Certification
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
