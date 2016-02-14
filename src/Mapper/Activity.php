<?php
// /src/Mapper/Activity.php

namespace Mapper;

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
     */
    public function findActivityById($id) {
        return $this->getRepository()->find($id);
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