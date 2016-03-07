<?php
// /src/Controller/EditorController.php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class EditorController
 *
 * @package Controller
 */
class EditorController extends Controller
{

    /**
     * Shows the editor for a new activity.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function newAction(Request $request, Response $response, $args = []) {
        // if user is not allowed to create a new activity, forward to login form
        if (!$this->getActivityService()->userMayCreate($this->getLoginService()->getLoggedInUser())) {
            return $this->getRedirectResponse($response, 'login');
        }

        $loggedInUser = $this->getLoginService()->getLoggedInUser();
        $params = [

        ];
        // add jwt token to parameters
        if ($loggedInUser !== null) {
            $params = array_merge($params, [
                'authToken' => $this->getJwtService()->generateToken($loggedInUser,
                    new \Acl\Scope([
                        'activity' => ['create']
                    ])
                ),
            ]);
        }
        $this->container['view']->render($response, 'pages/editor.twig', $params);
        return $response;
    }

    /**
     * Shows the editor for the specified activity.
     * Outputs a 404 status if the activity with the specified id was not found.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function editAction(Request $request, Response $response, $args = []) {
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['id']);

            // if user is not allowed to edit this activity, forward to login form
            if (!$this->getActivityService()->userMayEdit($activity, $this->getLoginService()->getLoggedInUser())) {
                return $this->getRedirectResponse($response, 'login');
            }

            $loggedInUser = $this->getLoginService()->getLoggedInUser();
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
            $acl = new \Acl\Acl();
            $scope = [
                'ownActivity' => ['edit', 'delete']
            ];
            $permissionActivity = [];
            if ($acl->isAllowed($loggedInUser->getRole()->value(), 'activity', 'edit')) {
                array_push($permissionActivity, 'edit');
            }
            if ($acl->isAllowed($loggedInUser->getRole()->value(), 'activity', 'delete')) {
                array_push($permissionActivity, 'delete');
            }
            array_merge($scope, ['activity' => $permissionActivity]);
            var_dump($scope);
            // add jwt token to parameters
            if ($loggedInUser !== null) {
                $params = array_merge($params, [
                    'authToken' => $this->getJwtService()->generateToken($loggedInUser,
                        new \Acl\Scope($scope)
                    ),
                ]);
            }
            $this->container['view']->render($response, 'pages/editor.twig', $params);
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
    protected function getLoginService() {
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

}