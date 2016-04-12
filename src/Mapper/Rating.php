<?php
// /src/Mapper/Rating.php

namespace Mapper;
use Exception\ActivityNotFoundException;
use Respect\Validation\Exceptions\NullTypeException;

/**
 * Mappers for Ratings.
 *
 * @package Mapper
 */
class Rating extends \Mapper\Mapper
{
    /**
     * Returns a rating based on its id.
     *
     * @param $id integer id of the rating to return
     * @return null|\Model\Activity\Rating
     * @throws \Exception\RatingNotFoundException if the rating with he specified id does not exist
     */
    public function findRatingById($id) {
        $rating =  $this->getRepository()->find($id);
        if (is_null($rating)) {
            throw new \Exception\RatingNotFoundException("Rating with the ID $id was not found");
        }
        return $rating;
    }

    /**
     * Returns the rating for a user and an activity.
     *
     * @param \Model\Activity\Activity $activity
     * @param \Model\User              $rater
     * @return null|\Model\Activity\Rating
     * @throws \Exception\RatingNotFoundException if the rating with the specified properties does not exist
     */
    public function findRatingByUserForActivity(\Model\Activity\Activity $activity, \Model\User $rater) {
        $rating = $this->getRepository()->findOneBy([
            'activity' => $activity->getId(),
            'rater' => $rater->getId(),
        ]);
        if (is_null($rating)) {
            throw new \Exception\RatingNotFoundException("Rating with specified properties not found");
        }
        return $rating;
    }

    /**
     * Returns all ratings.
     *
     * @return array
     */
    public function findAll() {
        return $this->getRepository()->findAll();
    }

    /**
     * Persists an object.
     *
     * @param $object
     */
    public function persist($object) {
        $this->em->persist($object);
    }

    /**
     * Removes a rating.
     *
     * @param \Model\Activity\Rating $rating
     */
    public function remove(\Model\Activity\Rating $rating) {
        $this->em->remove($rating);
    }

    /**
     * Get the repository for this mapper.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository() {
        return $this->em->getRepository('\Model\Activity\Rating');
    }

}