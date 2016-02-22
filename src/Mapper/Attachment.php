<?php
// /src/Mapper/Attachment.php

namespace Mapper;

/**
 * Mappers for Attachments.
 *
 * @package Mapper
 */
class Attachment extends \Mapper\Mapper
{
    /**
     * Returns an attachment based on its id.
     * Returns an attachment based on its id.
     *
     * @param $id integer id of the attachment to return
     * @return null|\Model\Activity\Attachment
     */
    public function findAttachmentById($id) {
        return $this->getRepository()->find($id);
    }

    /**
     * Returns all attachments.
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
     * Removes an attachment.
     *
     * @param \Model\Activity\Attachment $attachment
     */
    public function remove(\Model\Activity\Attachment $attachment) {
        $this->em->remove($attachment);
    }

    /**
     * Get the repository for this mapper.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('\Model\Activity\Attachment');
    }

}