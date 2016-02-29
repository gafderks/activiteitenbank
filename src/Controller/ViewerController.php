<?php
// /src/Controller/ViewerController.php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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

            $params = [
                'activity' => $activity,
                'userMayDelete' => $this->getActivityService()->userMayDelete($activity,
                    $this->getLoginService()->getLoggedInUser()),
                'userMayDownload' => $this->getActivityService()->userMayDownload($activity,
                    $this->getLoginService()->getLoggedInUser()),
                'userMayView' => $this->getActivityService()->userMayView($activity,
                    $this->getLoginService()->getLoggedInUser()),
                'userMayEdit' => $this->getActivityService()->userMayEdit($activity,
                    $this->getLoginService()->getLoggedInUser()),
                'userMayCreate' => $this->getActivityService()->userMayCreate($this->getLoginService()->getLoggedInUser()),
            ];
            $this->container->view->render($response, 'pages/viewer.twig', $params);
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
        return $this->container->service_login;
    }

    /**
     * Get the Activity service.
     *
     * @return \Service\ActivityService
     */
    protected function getActivityService() {
        return $this->container->service_activity;
    }

    /**
     * Get the activity mapper.
     *
     * @return \Mapper\Activity
     */
    protected function getActivityMapper() {
        return $this->container->mapper_activity;
    }

}