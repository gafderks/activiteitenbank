<?php
// /src/Controller/SettingsController.php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use ZxcvbnPhp\Zxcvbn;

/**
 * Class SettingsController
 *
 * @package Controller
 */
class SettingsController extends Controller
{

    private $aclPrivileges = [
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

        $scope = $this->getJwtService()->filterScope($this->aclPrivileges,
            $this->getLoginService()->getLoggedInUserRole()->value());

        $params = [
            'tokenScope' => $scope,
            'authToken' => $this->getJwtService()->generateToken($this->getLoginService()->getLoggedInUser(),
                new \Acl\Scope([
                    'token' => null,
                ]),
                [
                    'exp' => time() + (10 * 60) // token will be valid for 10 minutes
                ]
            ), // token that allows creating tokens
        ];

        $this->container['view']->render($response, 'pages/settings.twig', $params);
        return $response;
    }

    /**
     * Changes the password of the user that is logged in.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function changePasswordAction(Request $request, Response $response, $args = []) {
        if (!$this->container['acl']->isAllowed($this->getLoginService()->getLoggedInUserRole()->value(), 'settings')) {
            return $this->getRedirectResponse($response, 'login');
        }

        $scope = $this->getJwtService()->filterScope($this->aclPrivileges,
            $this->getLoginService()->getLoggedInUserRole()->value());

        $params = [
            'tokenScope' => $scope,
            'authToken' => $this->getJwtService()->generateToken($this->getLoginService()->getLoggedInUser(),
                new \Acl\Scope([
                    'token' => null,
                ]),
                [
                    'exp' => time() + (10 * 60) // token will be valid for 10 minutes
                ]
            ), // token that allows creating tokens
        ];

        // CHANGE PASSWORD
        // check current password
        if (!password_verify($request->getParsedBody()['old-password'], $this->getLoginService()->getLoggedInUser()->getPassword())) {
            $errors = [['message' => _("Your old password is not correct.")]];
            $params = array_merge($params, [
                'errors' => $errors,
            ]);
            $this->container['view']->render($response, 'pages/settings.twig', $params);
            return $response;
        }

        // check password strength
        $zxcvbn = new Zxcvbn();
        $strength = $zxcvbn->passwordStrength($request->getParsedBody()['password'])['score'];
        if ($strength < 3) {
            $errors = [['message' => _("The strength of the password is insufficient.")]];
            $params = array_merge($params, [
                'errors' => $errors,
            ]);
            $this->container['view']->render($response, 'pages/settings.twig', $params);
            return $response;
        }

        // update password
        $this->getLoginService()->changePassword(
            $this->getLoginService()->getLoggedInUser(),
            $request->getParsedBody()['password'],
            $request->getAttribute('ip_address')
        );

        // render form
        $params = array_merge($params, [
            'infos' => [['message' => _("Password has been reset successfully.")]]
        ]);

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