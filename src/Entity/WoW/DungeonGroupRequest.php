<?php

namespace App\Entity\WoW;

use App\Entity\BlizzardUser;
use App\Repository\WoW\DungeonGroupRequestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DungeonGroupRequestRepository::class)
 */
class DungeonGroupRequest
{
    public const STATUS_WAIT = 0;
    public const STATUS_REFUSED = 1;
    public const STATUS_ACCEPTED = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=BlizzardUser::class, inversedBy="dungeonGroupRequests")
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity=DungeonGroup::class, inversedBy="requests")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $dungeonGroup;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=WarcraftCharacter::class, inversedBy="requests")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $WarcraftCharacter;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?BlizzardUser
    {
        return $this->sender;
    }

    public function setSender(?BlizzardUser $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getDungeonGroup(): ?DungeonGroup
    {
        return $this->dungeonGroup;
    }

    public function setDungeonGroup(?DungeonGroup $dungeonGroup): self
    {
        $this->dungeonGroup = $dungeonGroup;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getWarcraftCharacter(): ?WarcraftCharacter
    {
        return $this->WarcraftCharacter;
    }

    public function setWarcraftCharacter(?WarcraftCharacter $WarcraftCharacter): self
    {
        $this->WarcraftCharacter = $WarcraftCharacter;

        return $this;
    }
}
