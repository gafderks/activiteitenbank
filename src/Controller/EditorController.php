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

    /**
     * Shows the editor for a new activity.
     */
    public function newAction() {
        $params = [

        ];
        $this->app->render('pages/editor.twig', $params);
    }

    /**
     * Shows the editor for the specified activity.
     * Outputs a 404 status if the activity with the specified id was not found.
     *
     * @param $id integer id of the activity to edit
     */
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