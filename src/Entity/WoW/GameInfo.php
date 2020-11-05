<?php


namespace App\Entity\WoW;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 */
class GameInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column
     */
    private $maxLevel;

    /**
     * @var array
     * @ORM\Column
     */
    private $classIds;

    /**
     * @var array
     * @ORM\Column
     */
    private $classIcons;

    /**
     * @var boolean
     * @ORM\Column
     */
    private $actual;

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
    public function getMaxLevel(): int
    {
        return $this->maxLevel;
    }

    /**
     * @param int $maxLevel
     */
    public function setMaxLevel(int $maxLevel): void
    {
        $this->maxLevel = $maxLevel;
    }

    /**
     * @return array
     */
    public function getClassIds(): array
    {
        return $this->classIds;
    }

    /**
     * @param array $classIds
     */
    public function setClassIds(array $classIds): void
    {
        $this->classIds = $classIds;
    }

    /**
     * @return array
     */
    public function getClassIcons(): array
    {
        return $this->classIcons;
    }

    /**
     * @param array $classIcons
     */
    public function setClassIcons(array $classIcons): void
    {
        $this->classIcons = $classIcons;
    }

    /**
     * @return bool
     */
    public function isActual(): bool
    {
        return $this->actual;
    }

    /**
     * @param bool $actual
     */
    public function setActual(bool $actual): void
    {
        $this->actual = $actual;
    }


}