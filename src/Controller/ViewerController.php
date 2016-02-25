<?php
// /src/Controller/ViewerController.php

namespace Controller;

/**
 * Class ViewerController
 *
 * @package Controller
 */
class ViewerController extends Controller
{

    /**
     * Shows the viewer for the specified activity.
     *
     * @param $id integer id of the activity to view
     */
    public function viewAction($id) {
        $activity = $this->getActivityMapper()->findActivityById($id);

        if (is_null($activity)) {
            $this->app->halt(404, json_encode("Activity with the specified ID was not found"));
        }

        $params = [
            'activity' => $activity,
        ];
        $this->app->render('pages/viewer.twig', $params);
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