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
        //print_r($this->getLoginService()); die;
        $email = $this->getLoginService()->findUserByUsername('gafderks')->getName();
        echo $email; die;

        $params = array();
        $this->app->render('pages/login.html', $params);
    }

    public function postAction() {

        $params = array(
            'login' => array(
                'username' => $this->app->request->post('username'),
                'errors' => [['message' => 'You did something wrong!']]
            ),
        );
        $this->app->render('pages/login.html', $params);
    }

    /**
     * Get the User service.
     *
     * @return \Service\LoginService
     */
    protected function getLoginService()
    {
        return $this->app->service_login;
    }

}