<?php


namespace App\Service\User;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function delete(User $user) {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function edit(User $user) {

    }

    public function create(User $user) {

    }
}