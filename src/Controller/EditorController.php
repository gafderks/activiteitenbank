<?php
// /src/Controller/EditorController.php

namespace Controller;

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
        $this->app->render('pages/editor.twig', $params);
    }

    public function editAction($id) {
        $activity = $this->getActivityMapper()->findActivityById($id);

        if (is_null($activity)) {
            $this->app->halt(404, json_encode("Activity with the specified ID was not found"));
        }

        $params = [
            'activity' => $activity,
        ];
        $this->app->render('pages/editor.twig', $params);
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
     * Get the activity mapper.
     *
     * @return \Mapper\Activity
     */
    protected function getActivityMapper() {
        return $this->app->mapper_activity;
    }

}