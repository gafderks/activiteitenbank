<?php
// /src/Controller/RatingController.php

namespace Controller;

use Model\Activity\Rating;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \Exception\RatingNotFoundException;

/**
 * Class RatingController
 *
 * @package Controller
 */
class RatingController extends Controller
{

    /**
     * Updates the rating for the specified activity for the specified user.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function updateAction(Request $request, Response $response, $args = []) {
        try {

            // if token is not allowed to update this rating, output 401
            if (!$this->getRatingService()->tokenMayRate($this->container['jwt'])) {
                return $this->getExceptionResponse($response,
                    new \Exception("You are not allowed to perform this action"), 401);
            }

            try {
                // get current rating
                $rating = $this->getRatingMapper()->findRatingByUserForActivity(
                    $this->getActivityMapper()->findActivityById($args['activityId']),
                    $this->getJwtService()->getUser($this->container['jwt'])
                );
            } catch (RatingNotFoundException $exception) {
                // rating does not exist yet, hence create it
                $rating = new \Model\Activity\Rating();
                $rating->setActivity($this->getActivityMapper()->findActivityById($args['activityId']));
                $rating->setRater($this->getJwtService()->getUser($this->container['jwt']));
            }

            // load input
            $input = json_decode($request->getBody());

            // set the properties of the rating
            $rating->setRate($input->rate);

            // save the rating
            $this->getRatingMapper()->persist($rating);

            // apply all modifications
            $this->getRatingMapper()->flush();
            // forces the entity manager to reload the rating in the next line
            $this->getRatingMapper()->clear();
            $newRating = $this->getRatingMapper()->findRatingById($rating->getId());

            // show response
            return $this->getJsonResponse($response, $newRating, 201);
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
     * Get the rating mapper.
     *
     * @return \Mapper\Rating
     */
    protected function getRatingMapper() {
        return $this->container['mapper_rating'];
    }

    /**
     * Get the rating service.
     *
     * @return \Service\RatingService
     */
    protected function getRatingService() {
        return $this->container['service_rating'];
    }

}