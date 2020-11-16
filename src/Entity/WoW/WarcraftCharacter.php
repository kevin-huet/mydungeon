<?php


namespace App\Entity\WoW;

use App\Entity\BlizzardUser;
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

}