<?php

namespace App\Repository\WoW;

use App\Entity\BlizzardUser;
use App\Entity\WoW\DungeonGroupRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DungeonGroupRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method DungeonGroupRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method DungeonGroupRequest[]    findAll()
 * @method DungeonGroupRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DungeonGroupRequestRepository extends ServiceEntityRepository
{
    public const STATUS_WAIT = 0;
    public const STATUS_REFUSED = 1;
    public const STATUS_ACCEPTED = 2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DungeonGroupRequest::class);
    }

    // /**
    //  * @return DungeonGroupRequest[] Returns an array of DungeonGroupRequest objects
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

    public function findOneById($value): ?DungeonGroupRequest
    {
        try {
            return $this->createQueryBuilder('d')
                ->andWhere('d.id = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    public function findAllWaitRequest(BlizzardUser $blizzardUser)
    {
        return $this->createQueryBuilder('d')
            ->where('d.status = :stat')
            ->setParameter('stat', self::STATUS_WAIT)
            ->andWhere('d.sender = :val')
            ->setParameter('val', $blizzardUser)
            ->orderBy('d.createdAt', 'DESC')
            ->setMaxResults(99)
            ->getQuery()
            ->getResult()
            ;
    }
}
