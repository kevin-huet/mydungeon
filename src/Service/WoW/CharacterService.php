<?php


namespace App\Service\WoW;


use App\Entity\BlizzardUser;
use App\Entity\LinkCharacterGroup;
use App\Entity\User;
use App\Entity\WoW\DungeonGroup;
use App\Entity\WoW\DungeonGroupRequest;
use App\Entity\WoW\WarcraftCharacter;
use Doctrine\ORM\EntityManagerInterface;

class CharacterService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function createEmptyCharacter($realm, $username)
    {
        $character = new WarcraftCharacter();
        $character->setRealm($realm);
        $character->setName($username);
        $this->em->persist($character);
        $this->em->flush();
    }
}