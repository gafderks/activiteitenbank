<?php
// /src/Controller/ViewerController.php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \Exception\RatingNotFoundException;

/**
 * Class ViewerController
 *
 * @package Controller
 */
class ViewerController extends Controller
{

    /**
     * Shows the viewer for the specified activity.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function viewAction(Request $request, Response $response, $args = []) {
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['id']);

            // if user is not allowed to view this activity, forward to login form
            if (!$this->getActivityService()->userMayView($activity, $this->getLoginService()->getLoggedInUser())) {
                return $this->getRedirectResponse($response, 'login');
            }

            $loggedInUser = $this->getLoginService()->getLoggedInUser();
            $params = [
                'activity' => $activity,
                'userMayDelete' => $this->getActivityService()->userMayDelete($activity, $loggedInUser),
                'userMayDownload' => $this->getActivityService()->userMayDownload($activity, $loggedInUser),
                'userMayView' => $this->getActivityService()->userMayView($activity, $loggedInUser),
                'userMayEdit' => $this->getActivityService()->userMayEdit($activity, $loggedInUser),
                'userMayCreate' => $this->getActivityService()->userMayCreate($loggedInUser),
                'userMayRate' => $this->getRatingService()->userMayRate($loggedInUser),
                'userMayComment' => $this->getCommentService()->userMayComment($loggedInUser),
                'ratings' => [
                    'amount' => count($activity->getRatings()),
                    'average' => $activity->getAverageRating(),
                ]
            ];
            if ($params['userMayRate']) {
                try {
                    $rating = $this->getRatingMapper()->findRatingByUserForActivity($activity, $loggedInUser);
                    $params['ratings']['ownRating'] = $rating->getRate();
                } catch (RatingNotFoundException $e) {
                    $params['ratings']['ownRating'] = 0;
                }
            }
    
            $params = array_merge($params, [
                // check if PDF generation is possible
                'pdfEnabled' => $this->container['config']['runEnvironment']['shellAccess'] ? true : false
            ]);

            // add jwt token to parameters
            if ($loggedInUser !== null) {
                $params = array_merge($params, [
                    'authToken' => $this->getJwtService()->generateToken($loggedInUser,
                        new \Acl\Scope([
                            'activity' => ['view', 'download', 'rate'],
                            'ownActivity' => ['view', 'download'],
                            'comment' => ['create'],
                        ])),
                ]);
            }
            $this->container['view']->render($response, 'pages/viewer.twig', $params);
            return $response;
        } catch(\Exception $exception) {
            return $this->getExceptionResponse($response, $exception, 404);
        }
    }

    /**
     * Get the Login service.
     *
     * @return \Service\LoginService
     */
    protected function getLoginService()
    {
        return $this->container['service_login'];
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
     * Get the Rating service.
     *
     * @return \Service\RatingService
     */
    protected function getRatingService() {
        return $this->container['service_rating'];
    }

    /**
     * Get the Comment service.
     *
     * @return \Service\CommentService
     */
    protected function getCommentService() {
        return $this->container['service_comment'];
    }

    /**
     * Get the Rating mapper.
     *
     * @return \Mapper\Rating
     */
    protected function getRatingMapper() {
        return $this->container['mapper_rating'];
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
     * Get the Jwt service.
     *
     * @return \Service\JwtService
     */
    protected function getJwtService() {
        return $this->container['service_jwt'];
    }

}