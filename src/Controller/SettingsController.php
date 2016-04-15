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
        // if user is not allowed see the settings page
        if (!$this->container['acl']->isAllowed($this->getLoginService()->getLoggedInUserRole()->value(), 'settings')) {
            return $this->getRedirectResponse($response, 'login');
        }

        $aclPrivileges = [
            'activity' => [
                'view', 'create', 'download', 'edit', 'delete', 'rate'
            ],
            'ownActivity' => [
                'view', 'create', 'download', 'edit', 'delete',
            ],
            'comment' => [
                'create', 'edit', 'delete',
            ]
        ];

        $acl = $this->container['acl'];
        $scope = [];
        foreach ($aclPrivileges as $resourceKey => $resource) {
            $allowedPrivileges = [];
            foreach ($resource as $privilege) {
                if ($acl->isAllowed($this->getLoginService()->getLoggedInUserRole()->value(),
                    $resourceKey, $privilege)) {
                    array_push($allowedPrivileges, $privilege);
                }
            }
            if (count($allowedPrivileges) > 0) {
                $scope[$resourceKey] = $allowedPrivileges;
            }
        }

        $params = [
            'tokenScope' => $scope,
            'authToken' => $this->getJwtService()->generateToken($this->getLoginService()->getLoggedInUser(),
                new \Acl\Scope([
                    'token' => null,
                ]),
                [
                    'exp' => time() + (10 * 60) // token will be valid for 10 minutes
                ]
            ),
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