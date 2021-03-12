<?php


namespace App\Service\WoW;


use App\Entity\BlizzardUser;
use App\Entity\LinkCharacterGroup;
use App\Entity\User;
use App\Entity\WoW\DungeonGroup;
use App\Entity\WoW\DungeonGroupRequest;
use App\Entity\WoW\WarcraftCharacter;
use Doctrine\ORM\EntityManagerInterface;

class DungeonGroupService
{
    public const STATUS_WAIT = 0;
    public const STATUS_REFUSED = 1;
    public const STATUS_ACCEPTED = 2;
    public const ROLE_DPS = "DPS";
    public const ROLE_TANK = "TANK";
    public const ROLE_HEAL = "HEAL";

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function createDungeonGroup($data, User $user)
    {
        /** @var WarcraftCharacter $character */
        $character = $data['character'];
        if (!$character)
            return;
        $linkCharacter = new LinkCharacterGroup();

        $group = new DungeonGroup();

        $linkCharacter->setCharacters($character);
        $linkCharacter->setDungeonGroup($group);
        $linkCharacter->setRole($data["role"]);

        $group->setTitle($data['name']);
        $group->setLeader($character);
        $group->setCreator($user->getBlizzardUser());
        $group->addMember($linkCharacter);

        $character->addLinkCharacterGroup($linkCharacter);

        if ($linkCharacter->getRole() == self::ROLE_DPS)
            $group->addDps();
        elseif ($linkCharacter->getRole() == self::ROLE_TANK)
            $group->addTank();
        elseif ($linkCharacter->getRole() == self::ROLE_HEAL)
            $group->addHeal();

        $this->em->persist($linkCharacter);
        $this->em->persist($group);
        $this->em->persist($character);
        $this->em->flush();
    }

    public function createRequest($data, BlizzardUser $blizzardUser, DungeonGroup $group)
    {
        $request = new DungeonGroupRequest();
        $role = "";
        if ($data['dps'])
            $role = $role."DPS;";
        if ($data['tank'])
            $role = $role."TANK;";
        if ($data['heal'])
            $role = $role."HEAL";
        $request->setRole($role);
        $request->setWarcraftCharacter($data['character']);
        $request->setDungeonGroup($group);
        $request->setStatus(self::STATUS_WAIT);
        $request->setSender($blizzardUser);
        $this->em->persist($request);
        $this->em->persist($group);
        $this->em->persist($blizzardUser);
        $this->em->flush();
    }

    public function changeRequestGroupStatus(BlizzardUser $blizzardUser, DungeonGroupRequest $groupRequest, int $status, $role)
    {
        $group = $groupRequest->getDungeonGroup();
        if ($group->getCreator()->getId() != $blizzardUser->getId())
            return "error: you're not the group creator";
        if ($groupRequest->getStatus() != self::STATUS_WAIT)
            return "error: already updated";
        if ($status == self::STATUS_ACCEPTED) {
            if ($role == self::ROLE_HEAL)
                $group->addHeal();
            elseif ($role == self::ROLE_DPS)
                $group->addDps();
            elseif ($role == self::ROLE_TANK)
                $group->addTank();

            $groupLinkCharacter = new LinkCharacterGroup();
            $groupLinkCharacter->setRole($role);
            $groupLinkCharacter->setCharacters($groupRequest->getWarcraftCharacter());
            $groupLinkCharacter->setDungeonGroup($group);
            $groupRequest->setStatus(self::STATUS_ACCEPTED);
            $group->addMember($groupLinkCharacter);
            $this->em->persist($groupLinkCharacter);
            $this->em->persist($group);
            $this->em->persist($groupRequest);
            $this->em->flush();

            return "accepted";
        } elseif ($status == self::STATUS_REFUSED) {
            $groupRequest->setStatus(self::STATUS_REFUSED);
            $this->em->persist($groupRequest);
            $this->em->flush();

            return "refused";
        } else
            return "wait";
    }

    public function deleteGroup(DungeonGroup $group)
    {

        $this->em->remove($group);
        $this->em->flush();
    }
}