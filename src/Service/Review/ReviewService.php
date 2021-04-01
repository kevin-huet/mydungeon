<?php


namespace App\Service\Review;


use App\Entity\Review\Review;
use App\Entity\WoW\WarcraftCharacter;

class ReviewService
{
    public function __construct() {

    }

    public function addReview(WarcraftCharacter $character)
    {
        $review = new Review();
        $character->addReview($review);
    }

    public function removeReview(WarcraftCharacter $character)
    {

    }

    public function changeReviewToOther(WarcraftCharacter $character)
    {

    }
}