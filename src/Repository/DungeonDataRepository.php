<?php

namespace App\Repository;

use App\Entity\DungeonData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DungeonData|null find($id, $lockMode = null, $lockVersion = null)
 * @method DungeonData|null findOneBy(array $criteria, array $orderBy = null)
 * @method DungeonData[]    findAll()
 * @method DungeonData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DungeonDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DungeonData::class);
    }

    // /**
    //  * @return DungeonData[] Returns an array of DungeonData objects
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
    public function findOneBySomeField($value): ?DungeonData
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function createIfNotExist($name, $id)
    {
        try {
            $value = $this->createQueryBuilder('e')
                ->andWhere('e.name = :val')
                ->andWhere('e.expansion = :val2')
                ->setParameter('val', $name)
                ->setParameter('val2', $id)
                ->getQuery()
                ->getOneOrNullResult();
            return $value ? $value : new DungeonData();
        } catch (NonUniqueResultException $e) {
        }
        return new DungeonData();
    }

    public function findDungeonLastExpansion($value)
    {
        return $this->createQueryBuilder('d')
            ->addSelect('r')
            ->leftJoin('d.expansion', 'r')
            ->where('r.lastExpansion = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }
}
