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
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function indexAction(Request $request, Response $response, $args = []) {
        // check if a user is already logged in
        if ($this->getLoginService()->getLoggedInUser() !== null) {
            return $this->getRedirectResponse($response, 'index');
        }

        $params = [

        ];

        if ($this->container['config']['facebook']['enableLogin']) {
            $params = array_merge($params, [
                'facebookLoginUrl' => $this->getFacebookService()->getFacebookLoginUrl(),
            ]);
        }

        $this->container['view']->render($response, 'pages/login.twig', $params);
        return $response;
    }

    /**
     * Tries to log in the user with the credentials in the request.
     * Redirects to the index page on success. Outputs login form on failure.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
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
            array_push($errors, ['message' => _('You need to supply an email address or a username')]);
        } else {

            // retrieve user with username $username or with email $username
            $user = $this->getUserService()->findUserByUsername($username);
            if ($user === null) {
                $user = $this->getUserService()->findUserByEmail($username);
            }

            // check if user exists
            if ($user === null) {
                array_push($errors, ['message' => _('Wrong username or password')]);
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
                    array_push($errors, ['message' => _('Wrong username or password')]);
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
        if ($this->container['config']['facebook']['enableLogin']) {
            $params = array_merge($params, [
                'facebookLoginUrl' => $this->getFacebookService()->getFacebookLoginUrl(),
            ]);
        }
        $this->container['view']->render($response, 'pages/login.twig', $params);
        return $response;
    }

    /**
     * Logs out a user.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function logoutAction(Request $request, Response $response, $args = []) {
        // logout user
        $this->getLoginService()->logoutUser();
        // redirect user
        return $this->getRedirectResponse($response, 'index');
    }

    /**
     * Handles Facebook login.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function facebookCallbackAction(Request $request, Response $response, $args = []) {
        // collect errors during login process
        $errors = [];

        // attempt to login user
        try {
            $loginSucceeded = $this->getFacebookService()->loginUser();
            if ($loginSucceeded) {
                return $this->getRedirectResponse($response, 'index');
            } else {
                array_push($errors, ['message' => _('Facebook login failed')]);
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
        if ($this->container['config']['facebook']['enableLogin']) {
            $params = array_merge($params, [
                'facebookLoginUrl' => $this->getFacebookService()->getFacebookLoginUrl(),
            ]);
        }
        $this->container['view']->render($response, 'pages/login.twig', $params);
        return $response;
    }

    /**
     * Handles password reset request.
     *
     * Flowchart: @see http://lh5.ggpht.com/-ke9GVduXaaY/T7rBCWHFkYI/AAAAAAAADmY/xvEOczv44Zg/Password-Reset5.png?imgmax=800
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function requestPasswordResetAction(Request $request, Response $response, $args = []) {
        // check if a user is already logged in
        if ($this->getLoginService()->getLoggedInUser() !== null) {
            return $this->getRedirectResponse($response, 'index');
        }

        // render form
        $params = [
            'recaptchaSiteKey' => $this->container['config']['recaptcha']['siteKey'],
        ];
        $this->container['view']->render($response, 'pages/password-reset-request.twig', $params);
        return $response;
    }

    /**
     * Sends email with password reset instructions.
     * If no account is linked to the email, a mail is sent to the address informing this.
     *
     * Flowchart: @see http://lh5.ggpht.com/-ke9GVduXaaY/T7rBCWHFkYI/AAAAAAAADmY/xvEOczv44Zg/Password-Reset5.png?imgmax=800
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function sendPasswordInstructionsAction(Request $request, Response $response, $args = []) {
        // check if a user is already logged in
        if ($this->getLoginService()->getLoggedInUser() !== null) {
            return $this->getRedirectResponse($response, 'index');
        }

        // collect errors during the process
        $errors = [];
        $infos = [];

        $recaptcha = new \ReCaptcha\ReCaptcha($this->container['config']['recaptcha']['secretKey']);
        $captchaResponse = $recaptcha->verify($request->getParsedBody()['g-recaptcha-response'],
            $request->getAttribute('ip_address'));
        if ($captchaResponse->isSuccess()) {
            // captcha is verified, send email
            try {
                $this->getLoginService()->sendPasswordResetEmail($request->getParsedBody()['email'],
                    $request->getAttribute('ip_address'));
                $infos = [["message" => _("Please check the inbox of your email for further instructions.")]];
            } catch (\Exception $e) {
                $errors = [["message" => $e->getMessage()]];
            }
        } else {
            $errors = [["message" => _("Captcha was not correctly answered")]];
        }


        // render form
        $params = [
            'login' => [
                'errors' => $errors,
                'infos' => $infos,
                'username' => $request->getParsedBody()['email'],
            ],
            'recaptchaSiteKey' => $this->container['config']['recaptcha']['siteKey'],
        ];
        $this->container['view']->render($response, 'pages/password-reset-request.twig', $params);
        return $response;
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
     * Get the Facebook service.
     *
     * @return \Service\FacebookService
     */
    protected function getFacebookService() {
        return $this->container['service_facebook'];
    }

    /**
     * Get the User service.
     *
     * @return \Service\UserService
     */
    protected function getUserService() {
        return $this->container['service_user'];
    }

}