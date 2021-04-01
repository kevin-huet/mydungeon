<?php

namespace App\Entity\Review;

use App\Entity\BlizzardUser;
use App\Entity\WoW\WarcraftCharacter;
use App\Repository\Review\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=BlizzardUser::class, inversedBy="reviews")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=WarcraftCharacter::class, inversedBy="reviews")
     */
    private $target;

    /**
     * @ORM\ManyToOne(targetEntity=ReviewCategory::class, inversedBy="reviews")
     */
    private $reviewCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?BlizzardUser
    {
        return $this->author;
    }

    public function setAuthor(?BlizzardUser $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTarget(): ?WarcraftCharacter
    {
        return $this->target;
    }

    public function setTarget(?WarcraftCharacter $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getPositive(): ?float
    {
        return $this->positive;
    }

    public function setPositive(float $positive): self
    {
        $this->positive = $positive;

        return $this;
    }

    public function getNegative(): ?float
    {
        return $this->negative;
    }

    public function setNegative(float $negative): self
    {
        $this->negative = $negative;

        return $this;
    }

    public function getToxicity(): ?float
    {
        return $this->toxicity;
    }

    public function setToxicity(float $toxicity): self
    {
        $this->toxicity = $toxicity;

        return $this;
    }

    public function getGoodPlayer(): ?float
    {
        return $this->goodPlayer;
    }

    public function setGoodPlayer(float $goodPlayer): self
    {
        $this->goodPlayer = $goodPlayer;

        return $this;
    }

    public function getBoostedAccount(): ?float
    {
        return $this->boostedAccount;
    }

    public function setBoostedAccount(float $boostedAccount): self
    {
        $this->boostedAccount = $boostedAccount;

        return $this;
    }

    public function getFriendly(): ?float
    {
        return $this->friendly;
    }

    public function setFriendly(float $friendly): self
    {
        $this->friendly = $friendly;

        return $this;
    }

    public function getLeaver(): ?float
    {
        return $this->leaver;
    }

    public function setLeaver(float $leaver): self
    {
        $this->leaver = $leaver;

        return $this;
    }

    public function getReviewCategory(): ?ReviewCategory
    {
        return $this->reviewCategory;
    }

    public function setReviewCategory(?ReviewCategory $reviewCategory): self
    {
        $this->reviewCategory = $reviewCategory;

        return $this;
    }
}
