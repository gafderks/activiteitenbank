<?php
// /src/Mapper/Activity.php

namespace Mapper;
use Exception\ActivityNotFoundException;
use Respect\Validation\Exceptions\NullTypeException;

/**
 * Mappers for Activities.
 *
 * @package Mapper
 */
class Activity extends \Mapper\Mapper
{
    /**
     * Returns an activity based on its id.
     *
     * @param $id integer id of the activity to return
     * @return null|\Model\Activity\Activity
     * @throws ActivityNotFoundException if the activity with he specified id does not exist
     */
    public function findActivityById($id) {
        $activity =  $this->getRepository()->find($id);
        if (is_null($activity)) {
            throw new \Exception\ActivityNotFoundException("Activity with the ID $id was not found");
        }
        return $activity;
    }

    /**
     * Returns all activities.
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
     * Removes an activity.
     *
     * @param \Model\Activity\Activity $activity
     */
    public function remove(\Model\Activity\Activity $activity) {
        $this->em->remove($activity);
    }

    /**
     * Get the repository for this mapper.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('\Model\Activity\Activity');
    }

}