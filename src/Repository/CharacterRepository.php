<?php


namespace App\Repository;


use App\Entity\WoW\WarcraftCharacter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WarcraftCharacter|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarcraftCharacter|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarcraftCharacter[]    findAll()
 * @method WarcraftCharacter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarcraftCharacter::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findExistCharacter($username, $realm)
    {
            return $this->createQueryBuilder('u')
                ->andWhere('u.name = :name')
                ->setParameter('name', $username)
                ->andWhere('u.realm = :realm_')
                ->setParameter('realm_', $realm)
                ->getQuery()
                ->getResult();
    }
}
