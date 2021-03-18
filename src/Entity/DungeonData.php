<?php

namespace App\Entity;

use App\Entity\WoW\Expansion;
use App\Repository\DungeonDataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DungeonDataRepository::class)
 */
class DungeonData
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $media;

    /**
     * @ORM\ManyToOne(targetEntity=Expansion::class, inversedBy="dungeons")
     */
    private $expansion;

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

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getExpansion(): ?Expansion
    {
        return $this->expansion;
    }

    public function setExpansion(?Expansion $expansion): self
    {
        $this->expansion = $expansion;

        return $this;
    }
}
