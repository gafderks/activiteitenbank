<?php
// /src/Controller/EditorController.php

namespace Controller;
use \Model\Enum\ActivityArea;

/**
 * Class EditorController
 *
 * @package Controller
 */
class EditorController extends Controller
{

    public function newAction() {
        // if no user is logged in, redirect to login page
        if (null === $this->getLoginService()->getLoggedInUser()) {
            $this->app->redirect($this->app->urlFor('login-form'));
        }

        $params = [

        ];
        $this->app->render('pages/editor.html', $params);
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

}