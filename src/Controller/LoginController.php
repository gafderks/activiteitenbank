<?php
// /src/Controller/LoginController.php

namespace Controller;

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
    public function indexAction() {
        // check if a user is already logged in
        if (null !== $this->getLoginService()->getLoggedInUser()) {
            $this->app->redirect($this->app->urlFor('index'));
        }

        $params = [];
        $this->app->render('pages/login.twig', $params);
    }

    /**
     * Tries to log in the user with the credentials in the request.
     * Redirects to the index page on success. Outputs login form on failure.
     *
     * @throws \Exception when a user is already logged in
     */
    public function postAction() {

        // collect errors during login process
        $errors = [];

        // load credentials that were given
        $username = $this->app->request->post('username');
        $password = $this->app->request->post('password');
        $referrer = $this->app->request->post('referrer');

        // retrieve user with username $username or with email $username
        $user = $this->getUserService()->findUserByUsername($username);
        if (null === $user) {
            $user = $this->getUserService()->findUserByEmail($username);
        }

        // check if user exists
        if (null === $user) {
            array_push($errors, ['message' => 'Wrong username or password']);
        }

        // attempt to login user
        if (null !== $user) {
            if ($this->getLoginService()->loginUser($user, $password)) {
                if (!empty($referrer)) {
                    $this->app->redirect($referrer);
                } else {
                    $this->app->redirect($this->app->urlFor('index'));
                }
            } else {
                array_push($errors, ['message' => 'Wrong username or password']);
            }
        }

        // render form
        $params = [
            'login' => [
                'username' => $this->app->request->post('username'),
                'errors' => $errors
            ],
        ];
        $this->app->render('pages/login.twig', $params);
    }

    /**
     * Logs out a user.
     */
    public function logoutAction() {
        // logout user
        $this->getLoginService()->logoutUser();
        // redirect user
        $this->app->redirect($this->app->urlFor('index'));
    }

    /**
     * Get the Login service.
     *
     * @return \Service\LoginService
     */
    protected function getLoginService()
    {
        return $this->app->service_login;
    }

    /**
     * Get the User service.
     *
     * @return \Service\UserService
     */
    protected function getUserService()
    {
        return $this->app->service_user;
    }

}