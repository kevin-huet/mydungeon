<?php

namespace App\Repository;

use App\Entity\ClassInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClassInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassInfo[]    findAll()
 * @method ClassInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassInfo::class);
    }

    // /**
    //  * @return ClassInfo[] Returns an array of ClassInfo objects
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
    public function findOneBySomeField($value): ?ClassInfo
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
