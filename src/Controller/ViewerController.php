<?php
// /src/Controller/ViewerController.php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
    public function viewAction(Request $request, Response $response, $args = []) {
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['id']);

            $params = [
                'activity' => $activity,
            ];
            $this->container->view->render($response, 'pages/viewer.twig', $params);
            return $response;
        } catch(\Exception $exception) {
            return $this->getExceptionResponse($response, $exception, 404);
        }
    }

    /**
     * Get the Login service.
     *
     * @return \Service\LoginService
     */
    protected function getLoginService()
    {
        return $this->container->service_login;
    }

    /**
     * Get the activity mapper.
     *
     * @return \Mapper\Activity
     */
    protected function getActivityMapper() {
        return $this->container->mapper_activity;
    }

}