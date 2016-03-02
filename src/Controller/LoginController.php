<?php
// /src/Controller/LoginController.php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class LoginController
 *
 * @package Controller
 */
class LoginController extends Controller
{

    /**
     * Shows the login form.
     * If a session already exists, a redirect is done to the index page.
     */
    public function indexAction(Request $request, Response $response, $args = []) {
        // check if a user is already logged in
        if ($this->getLoginService()->getLoggedInUser() !== null) {
            return $this->getRedirectResponse($response, 'index');
        }

        $params = [

        ];

        if ($this->container->config['facebook']['enableLogin']) {
            $params = array_merge($params, [
                'facebookLoginUrl' => $this->getFacebookService()->getFacebookLoginUrl(),
            ]);
        }

        $this->container->view->render($response, 'pages/login.twig', $params);
        return $response;
    }

    /**
     * Tries to log in the user with the credentials in the request.
     * Redirects to the index page on success. Outputs login form on failure.
     *
     * @throws \Exception when a user is already logged in
     */
    public function postAction(Request $request, Response $response, $args = []) {

        // collect errors during login process
        $errors = [];

        // load credentials that were given
        $username = $request->getParsedBody()['username'];
        $password = $request->getParsedBody()['password'];
        $referrer = $request->getParsedBody()['referrer'];

        if (empty($username)) {
            array_push($errors, ['message' => 'You need to supply an email address or a username']);
        } else {

            // retrieve user with username $username or with email $username
            $user = $this->getUserService()->findUserByUsername($username);
            if ($user === null) {
                $user = $this->getUserService()->findUserByEmail($username);
            }

            // check if user exists
            if ($user === null) {
                array_push($errors, ['message' => 'Wrong username or password']);
            }

            // attempt to login user
            if ($user !== null) {
                if ($this->getLoginService()->loginUser($user, $password)) {
                    if (!empty($referrer)) {
                        return $response->withStatus(301)->withHeader('Location', $referrer);
                    } else {
                        return $this->getRedirectResponse($response, 'index');
                    }
                } else {
                    array_push($errors, ['message' => 'Wrong username or password']);
                }
            }
        }

        // render form
        $params = [
            'login' => [
                'username' => $request->getParsedBody()['username'],
                'errors' => $errors
            ],
        ];
        if ($this->container->config['facebook']['enableLogin']) {
            $params = array_merge($params, [
                'facebookLoginUrl' => $this->getFacebookService()->getFacebookLoginUrl(),
            ]);
        }
        $this->container->view->render($response, 'pages/login.twig', $params);
        return $response;
    }

    /**
     * Logs out a user.
     */
    public function logoutAction(Request $request, Response $response, $args = []) {
        // logout user
        $this->getLoginService()->logoutUser();
        // redirect user
        return $this->getRedirectResponse($response, 'index');
    }

    public function facebookCallbackAction(Request $request, Response $response, $args = []) {
        // collect errors during login process
        $errors = [];

        // attempt to login user
        try {
            $loginSucceeded = $this->getFacebookService()->loginUser();
            if ($loginSucceeded) {
                return $this->getRedirectResponse($response, 'index');
            } else {
                array_push($errors, ['message' => 'Facebook login failed']);
            }
        } catch (\Exception $e) {
            array_push($errors, ['message' => $e->getMessage()]);
        }

        // render form
        $params = [
            'login' => [
                'errors' => $errors,
            ],
        ];
        if ($this->container->config['facebook']['enableLogin']) {
            $params = array_merge($params, [
                'facebookLoginUrl' => $this->getFacebookService()->getFacebookLoginUrl(),
            ]);
        }
        $this->container->view->render($response, 'pages/login.twig', $params);
        return $response;
    }

    /**
     * Get the Login service.
     *
     * @return \Service\LoginService
     */
    protected function getLoginService() {
        return $this->container->service_login;
    }

    /**
     * Get the Facebook service.
     *
     * @return \Service\FacebookService
     */
    protected function getFacebookService() {
        return $this->container->service_facebook;
    }

    /**
     * Get the User service.
     *
     * @return \Service\UserService
     */
    protected function getUserService() {
        return $this->container->service_user;
    }

}