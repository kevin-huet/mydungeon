<?php

namespace App\Entity\WoW;

use App\Entity\DungeonData;
use App\Repository\WoW\ExpansionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExpansionRepository::class)
 */
class Expansion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $expansionId;

    /**
     * @ORM\OneToMany(targetEntity=DungeonData::class, mappedBy="expansion")
     */
    private $dungeons;

    /**
     * @ORM\Column(type="boolean")
     */
    private $lastExpansion;

    public function __construct()
    {
        $this->dungeons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExpansionId(): ?int
    {
        return $this->expansionId;
    }

    public function setExpansionId(int $expansionId): self
    {
        $this->expansionId = $expansionId;

        return $this;
    }

    /**
     * @return Collection|DungeonData[]
     */
    public function getDungeons(): Collection
    {
        return $this->dungeons;
    }

    public function addDungeon(DungeonData $dungeon): self
    {
        if (!$this->dungeons->contains($dungeon)) {
            $this->dungeons[] = $dungeon;
            $dungeon->setExpansion($this);
        }

        return $this;
    }

    public function removeDungeon(DungeonData $dungeon): self
    {
        if ($this->dungeons->removeElement($dungeon)) {
            // set the owning side to null (unless already changed)
            if ($dungeon->getExpansion() === $this) {
                $dungeon->setExpansion(null);
            }
        }

        return $this;
    }

    public function getLastExpansion(): ?bool
    {
        return $this->lastExpansion;
    }

    public function setLastExpansion(bool $lastExpansion): self
    {
        $this->lastExpansion = $lastExpansion;

        return $this;
    }
}
