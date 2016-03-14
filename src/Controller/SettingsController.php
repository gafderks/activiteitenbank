<?php
// /src/Controller/SettingsController.php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class SettingsController
 *
 * @package Controller
 */
class SettingsController extends Controller
{

    /**
     * Shows the settings menu.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function indexAction(Request $request, Response $response, $args = []) {
//            $activity = $this->getActivityMapper()->findActivityById($args['id']);
//
//            // if user is not allowed to view this activity, forward to login form
//            if (!$this->getActivityService()->userMayView($activity, $this->getLoginService()->getLoggedInUser())) {
//                return $this->getRedirectResponse($response, 'login');
//            }
//
//            $loggedInUser = $this->getLoginService()->getLoggedInUser();
//            $params = [
//                'activity' => $activity,
//                'userMayDelete' => $this->getActivityService()->userMayDelete($activity, $loggedInUser),
//                'userMayDownload' => $this->getActivityService()->userMayDownload($activity, $loggedInUser),
//                'userMayView' => $this->getActivityService()->userMayView($activity, $loggedInUser),
//                'userMayEdit' => $this->getActivityService()->userMayEdit($activity, $loggedInUser),
//                'userMayCreate' => $this->getActivityService()->userMayCreate($loggedInUser),
//            ];
//            // add jwt token to parameters
//            if ($loggedInUser !== null) {
//                $params = array_merge($params, [
//                    'authToken' => $this->getJwtService()->generateToken($loggedInUser,
//                        new \Acl\Scope([
//                            'activity' => ['view', 'download'],
//                            'ownActivity' => ['view', 'download']
//                        ])),
//                ]);
//            }
        $params = [

        ];
        $this->container['view']->render($response, 'pages/settings.twig', $params);
        return $response;
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