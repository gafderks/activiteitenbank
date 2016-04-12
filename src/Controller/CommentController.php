<?php
// /src/Controller/CommentController.php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class CommentController
 *
 * @package Controller
 */
class CommentController extends Controller
{

    /**
     * Creates a comment for the specified activity for the specified user.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function createAction(Request $request, Response $response, $args = []) {
        try {

            // if token is not allowed to create a comment, output 401
            if (!$this->getCommentService()->tokenMayComment($this->container['jwt'])) {
                return $this->getExceptionResponse($response,
                    new \Exception("You are not allowed to perform this action"), 401);
            }

            $comment = new \Model\Activity\Comment();
            $comment->setCommenter($this->getJwtService()->getUser($this->container['jwt']));
            $comment->setActivity($this->getActivityMapper()->findActivityById($args['activityId']));

            // load input
            $input = json_decode($request->getBody());

            // set the properties of the comment
            $comment->setComment($input->comment);
            $comment->setDidIt($input->didIt);

            // save the comment
            $this->getCommentMapper()->persist($comment);

            // apply all modifications
            $this->getCommentMapper()->flush();
            // forces the entity manager to reload the comment in the next line
            $this->getCommentMapper()->clear();
            $newComment = $this->getCommentMapper()->findCommentById($comment->getId());

            // show response
            return $this->getJsonResponse($response, $newComment, 201);
        } catch (\Exception $exception) {
            return $this->getExceptionResponse($response, $exception, 404);
        }
    }

    /**
     * Get the Activity service.
     *
     * @return \Service\ActivityService
     */
    protected function getActivityService() {
        return $this->container['service_activity'];
    }

    /**
     * Get the JWT service.
     *
     * @return \Service\JwtService
     */
    protected function getJwtService() {
        return $this->container['service_jwt'];
    }

    /**
     * Get the activity mapper.
     *
     * @return \Mapper\Activity
     */
    protected function getActivityMapper() {
        return $this->container['mapper_activity'];
    }

    /**
     * Get the comment mapper.
     *
     * @return \Mapper\Comment
     */
    protected function getCommentMapper() {
        return $this->container['mapper_comment'];
    }

    /**
     * Get the comment service.
     *
     * @return \Service\CommentService
     */
    protected function getCommentService() {
        return $this->container['service_comment'];
    }

}