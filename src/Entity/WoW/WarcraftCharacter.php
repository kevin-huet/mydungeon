<?php


namespace App\Entity\WoW;

use App\Entity\BlizzardUser;
use App\Entity\LinkCharacterGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 */
class WarcraftCharacter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column
     */
    private $playableClass;

    /**
     * @var string
     * @ORM\Column
     */
    private $name;

    /**
     * @var string
     * @ORM\Column
     */
    private $realm;

    /**
     * @ORM\ManyToOne(targetEntity=BlizzardUser::class, inversedBy="characters")
     */
    private $blizzardUser;

    /**
     * @ORM\OneToMany(targetEntity=DungeonGroup::class, mappedBy="leader")
     */
    private $dungeonGroups;

    /**
     * @ORM\OneToMany(targetEntity=LinkCharacterGroup::class, mappedBy="characters")
     */
    private $linkCharacterGroups;

    /**
     * @ORM\OneToMany(targetEntity=DungeonGroupRequest::class, mappedBy="WarcraftCharacter")
     */
    private $requests;

    public function __construct()
    {
        $this->dungeonGroups = new ArrayCollection();
        $this->linkCharacterGroups = new ArrayCollection();
        $this->requests = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getPlayableClass(): int
    {
        return $this->playableClass;
    }

    /**
     * @param int $playableClass
     */
    public function setPlayableClass(int $playableClass): void
    {
        $this->playableClass = $playableClass;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getRealm(): string
    {
        return $this->realm;
    }

    /**
     * @param string $realm
     */
    public function setRealm(string $realm): void
    {
        $this->realm = $realm;
    }

    public function getBlizzardUser(): ?BlizzardUser
    {
        return $this->blizzardUser;
    }

    public function setBlizzardUser(?BlizzardUser $blizzardUser): self
    {
        $this->blizzardUser = $blizzardUser;

        return $this;
    }

    /**
     * @return Collection|DungeonGroup[]
     */
    public function getDungeonGroups(): Collection
    {
        return $this->dungeonGroups;
    }

    public function addDungeonGroup(DungeonGroup $dungeonGroup): self
    {
        if (!$this->dungeonGroups->contains($dungeonGroup)) {
            $this->dungeonGroups[] = $dungeonGroup;
            $dungeonGroup->setLeader($this);
        }

        return $this;
    }

    public function removeDungeonGroup(DungeonGroup $dungeonGroup): self
    {
        if ($this->dungeonGroups->removeElement($dungeonGroup)) {
            // set the owning side to null (unless already changed)
            if ($dungeonGroup->getLeader() === $this) {
                $dungeonGroup->setLeader(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LinkCharacterGroup[]
     */
    public function getLinkCharacterGroups(): Collection
    {
        return $this->linkCharacterGroups;
    }

    public function addLinkCharacterGroup(LinkCharacterGroup $linkCharacterGroup): self
    {
        if (!$this->linkCharacterGroups->contains($linkCharacterGroup)) {
            $this->linkCharacterGroups[] = $linkCharacterGroup;
            $linkCharacterGroup->setCharacters($this);
        }

        return $this;
    }

    public function removeLinkCharacterGroup(LinkCharacterGroup $linkCharacterGroup): self
    {
        if ($this->linkCharacterGroups->removeElement($linkCharacterGroup)) {
            // set the owning side to null (unless already changed)
            if ($linkCharacterGroup->getCharacters() === $this) {
                $linkCharacterGroup->setCharacters(null);
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
            $request->setWarcraftCharacter($this);
        }

        return $this;
    }

    public function removeRequest(DungeonGroupRequest $request): self
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getWarcraftCharacter() === $this) {
                $request->setWarcraftCharacter(null);
            }
        }

        return $this;
    }

}