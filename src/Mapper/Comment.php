<?php
// /src/Mapper/Comment.php

namespace Mapper;
use Respect\Validation\Exceptions\NullTypeException;

/**
 * Mappers for Comments.
 *
 * @package Mapper
 */
class Comment extends \Mapper\Mapper
{
    /**
     * Returns a comment based on its id.
     *
     * @param $id integer id of the comment to return
     * @return null|\Model\Activity\Comment
     * @throws \Exception\CommentNotFoundException if the comment with he specified id does not exist
     */
    public function findCommentById($id) {
        $comment =  $this->getRepository()->find($id);
        if (is_null($comment)) {
            throw new \Exception\CommentNotFoundException("Comment with the ID $id was not found");
        }
        return $comment;
    }

    /**
     * Returns all comments.
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
     * Removes a comment.
     *
     * @param \Model\Activity\Comment $comment
     */
    public function remove(\Model\Activity\Comment $comment) {
        $this->em->remove($comment);
    }

    /**
     * Get the repository for this mapper.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository() {
        return $this->em->getRepository('\Model\Activity\Comment');
    }

}