<?php

namespace App\Entity\WoW;

use App\Entity\BlizzardUser;
use App\Entity\LinkCharacterGroup;
use App\Repository\WoW\DungeonGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DungeonGroupRepository::class)
 */
class DungeonGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=BlizzardUser::class, inversedBy="dungeonGroup")
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity=WarcraftCharacter::class, inversedBy="dungeonGroups")
     */
    private $leader;

    /**
     * @ORM\OneToMany(targetEntity=LinkCharacterGroup::class, mappedBy="dungeonGroup")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity=DungeonGroupRequest::class, mappedBy="dungeonGroup")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $requests;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $tank;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $dps;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $heal;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->requests = new ArrayCollection();
        $this->dps = 0;
        $this->heal = 0;
        $this->tank = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreator(): ?BlizzardUser
    {
        return $this->creator;
    }

    public function setCreator(?BlizzardUser $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getLeader(): ?WarcraftCharacter
    {
        return $this->leader;
    }

    public function setLeader(?WarcraftCharacter $leader): self
    {
        $this->leader = $leader;

        return $this;
    }

    /**
     * @return Collection|LinkCharacterGroup[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(LinkCharacterGroup $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setDungeonGroup($this);
        }

        return $this;
    }

    public function removeMember(LinkCharacterGroup $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getDungeonGroup() === $this) {
                $member->setDungeonGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DungeonGroupRequest[]
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(DungeonGroupRequest $request): self
    {
        if (!$this->requests->contains($request)) {
            $this->requests[] = $request;
            $request->setDungeonGroup($this);
        }

        return $this;
    }

    public function removeRequest(DungeonGroupRequest $request): self
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getDungeonGroup() === $this) {
                $request->setDungeonGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getTank()
    {
        return $this->tank;
    }

    /**
     * @param int $tank
     */
    public function setTank(int $tank): void
    {
        $this->tank = $tank;
    }

    /**
     * @return int
     */
    public function getDps()
    {
        return $this->dps;
    }

    /**
     * @param int $dps
     */
    public function setDps(int $dps): void
    {
        $this->dps = $dps;
    }

    /**
     * @return int
     */
    public function getHeal()
    {
        return $this->heal;
    }

    /**
     * @param int $heal
     */
    public function setHeal(int $heal): void
    {
        $this->heal = $heal;
    }

    public function addTank()
    {
        $this->tank++;
        return $this->tank;
    }

    public function addDps()
    {
        $this->dps++;
        return $this->dps;
    }

    public function addHeal()
    {
        $this->heal++;
        return $this->heal;
    }
}
