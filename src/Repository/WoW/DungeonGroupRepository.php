<?php

namespace App\Repository\WoW;

use App\Entity\WoW\DungeonGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DungeonGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method DungeonGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method DungeonGroup[]    findAll()
 * @method DungeonGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DungeonGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DungeonGroup::class);
    }

    // /**
    //  * @return DungeonGroup[] Returns an array of DungeonGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DungeonGroup
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
