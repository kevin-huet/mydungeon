<?php

namespace App\Repository;

use App\Entity\LinkCharacterGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LinkCharacterGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkCharacterGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkCharacterGroup[]    findAll()
 * @method LinkCharacterGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkCharacterGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkCharacterGroup::class);
    }

    // /**
    //  * @return LinkCharacterGroup[] Returns an array of LinkCharacterGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LinkCharacterGroup
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
