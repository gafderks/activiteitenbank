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

    public function indexAction() {
        $params = [];
        $this->app->render('pages/login.html', $params);
    }

    public function postAction() {

        // collect errors during login process
        $errors = [];

        // load credentials that were given
        $username = $this->app->request->post('username');
        $password = $this->app->request->post('password');
        $referrer = $_SERVER['HTTP_REFERER'];

        // retrieve user with username $username or with email $username
        $user = $this->getUserService()->findUserByUsername($username);
        if (is_null($user)) {
            $user = $this->getUserService()->findUserByEmail($username);
        }

        // check if user exists
        if (is_null($user)) {
            array_push($errors, ['message' => 'Wrong username or password']);
        }

        // attempt to login user
        if (!is_null($user)) {
            if ($this->getLoginService()->loginUser($user, $password)) {
                if (isset($referrer)) {
                    header("Location: $referrer");
                } else {
                    header("Location: index.php");
                }
            } else {
                array_push($errors, ['message' => 'Wrong username or password']);
            }
        }


        $params = [
            'login' => [
                'username' => $this->app->request->post('username'),
                'errors' => $errors
            ],
        ];
        $this->app->render('pages/login.html', $params);
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