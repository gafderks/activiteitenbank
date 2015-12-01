<?php
// /src/Controller/LoginController.php

namespace Controller;


class LoginController extends Controller
{

    public function indexAction() {
        $params = array();
        $this->app->render('pages/login.html', $params);
    }

    public function postAction() {
        $params = array(
            'login' => array(
                'username' => $this->app->request->post('username'),
                'errors' => array(array('message' => 'You did something wrong!'))
            ),
        );
        $this->app->render('pages/login.html', $params);
    }

}