<?php

namespace App\Entity;

use App\Entity\WoW\DungeonGroup;
use App\Entity\WoW\WarcraftCharacter;
use App\Repository\LinkCharacterGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LinkCharacterGroupRepository::class)
 */
class LinkCharacterGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=WarcraftCharacter::class, inversedBy="linkCharacterGroups")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $characters;

    /**
     * @ORM\ManyToOne(targetEntity=DungeonGroup::class, inversedBy="members")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $dungeonGroup;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $role;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $keyMin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $keyMax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $scoreAverage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharacters(): ?WarcraftCharacter
    {
        return $this->characters;
    }

    public function setCharacters(?WarcraftCharacter $characters): self
    {
        $this->characters = $characters;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getKeyMin(): ?int
    {
        return $this->keyMin;
    }

    public function setKeyMin(?int $keyMin): self
    {
        $this->keyMin = $keyMin;

        return $this;
    }

    public function getKeyMax(): ?int
    {
        return $this->keyMax;
    }

    public function setKeyMax(?int $keyMax): self
    {
        $this->keyMax = $keyMax;

        return $this;
    }

    public function getScoreAverage(): ?float
    {
        return $this->scoreAverage;
    }

    public function setScoreAverage(?float $scoreAverage): self
    {
        $this->scoreAverage = $scoreAverage;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}
