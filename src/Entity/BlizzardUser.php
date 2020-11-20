<?php


namespace App\Entity;

<<<<<<< HEAD
use App\Entity\WoW\Character;
=======
use App\Entity\WoW\DungeonGroup;
use App\Entity\WoW\DungeonGroupRequest;
use App\Entity\WoW\WarcraftCharacter;
>>>>>>> release/0.1.0
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class BlizzardUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="BlizzardUser", cascade={"persist", "remove"})
     */
    private $user;

    /**
<<<<<<< HEAD
     * @ORM\OneToMany(targetEntity=Character::class, mappedBy="blizzardUser")
     */
    private $characters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
=======
     * @ORM\OneToMany(targetEntity=WarcraftCharacter::class, mappedBy="blizzardUser")
     */
    private $characters;

    /**
     * @ORM\OneToMany(targetEntity=DungeonGroup::class, mappedBy="creator")
     */
    private $dungeonGroup;

    /**
     * @ORM\OneToMany(targetEntity=DungeonGroupRequest::class, mappedBy="sender")
     */
    private $dungeonGroupRequests;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->dungeonGroup = new ArrayCollection();
        $this->dungeonGroupRequests = new ArrayCollection();
>>>>>>> release/0.1.0
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
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newBlizzardUser = null === $user ? null : $this;
        if ($user->getBlizzardUser() !== $newBlizzardUser) {
            $user->setBlizzardUser($newBlizzardUser);
        }

        return $this;
    }

    /**
<<<<<<< HEAD
     * @return Collection|Character[]
=======
     * @return Collection|WarcraftCharacter[]
>>>>>>> release/0.1.0
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

<<<<<<< HEAD
    public function addCharacter(Character $character): self
=======
    public function addCharacter(WarcraftCharacter $character): self
>>>>>>> release/0.1.0
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->setBlizzardUser($this);
        }

        return $this;
    }

<<<<<<< HEAD
    public function removeCharacter(Character $character): self
=======
    public function removeCharacter(WarcraftCharacter $character): self
>>>>>>> release/0.1.0
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getBlizzardUser() === $this) {
                $character->setBlizzardUser(null);
            }
        }

        return $this;
    }

<<<<<<< HEAD
=======
    /**
     * @return Collection|DungeonGroup[]
     */
    public function getDungeonGroup(): Collection
    {
        return $this->dungeonGroup;
    }

    public function addDungeonGroup(DungeonGroup $leader): self
    {
        if (!$this->dungeonGroup->contains($leader)) {
            $this->dungeonGroup[] = $leader;
            $leader->setCreator($this);
        }

        return $this;
    }

    public function removeDungeonGroup(DungeonGroup $leader): self
    {
        if ($this->dungeonGroup->removeElement($leader)) {
            // set the owning side to null (unless already changed)
            if ($leader->getCreator() === $this) {
                $leader->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DungeonGroupRequest[]
     */
    public function getDungeonGroupRequests(): Collection
    {
        return $this->dungeonGroupRequests;
    }

    public function addDungeonGroupRequest(DungeonGroupRequest $dungeonGroupRequest): self
    {
        if (!$this->dungeonGroupRequests->contains($dungeonGroupRequest)) {
            $this->dungeonGroupRequests[] = $dungeonGroupRequest;
            $dungeonGroupRequest->setSender($this);
        }

        return $this;
    }

    public function removeDungeonGroupRequest(DungeonGroupRequest $dungeonGroupRequest): self
    {
        if ($this->dungeonGroupRequests->removeElement($dungeonGroupRequest)) {
            // set the owning side to null (unless already changed)
            if ($dungeonGroupRequest->getSender() === $this) {
                $dungeonGroupRequest->setSender(null);
            }
        }

        return $this;
    }

>>>>>>> release/0.1.0

}