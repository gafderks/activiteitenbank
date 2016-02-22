<?php
// /src/Controller/AttachmentController.php

namespace Controller;

/**
 * Class AttachmentController
 *
 * @package Controller
 */
class AttachmentController extends Controller
{

    public function uploadAction($activityId) {
        $activity = $this->getActivityMapper()->findActivityById($activityId);

        if (is_null($activity)) {
            $this->app->halt(404, json_encode("Activity with the specified ID was not found"));
        }

        try {
            $attachment = $this->getAttachmentService()->uploadAttachment('file');
            $attachment->setActivity($activity);
            // store attachment in database
            $this->getActivityMapper()->persist($attachment);
            $this->getActivityMapper()->flush();

            $this->app->response->setStatus(201);
            $this->app->response->headers->set('Content-Type', 'application/json');
            $this->app->response->body(json_encode($attachment));
        } catch (\Exception $ex) {
            $this->app->halt(400, json_encode($ex->getMessage()));
        }
    }

    public function downloadAction($activityId, $attachmentId) {
        $activity = $this->getActivityMapper()->findActivityById($activityId);

        if (is_null($activity)) {
            $this->app->halt(404, json_encode("Activity with the specified ID was not found"));
        }

        $attachment = $this->getAttachmentMapper()->findAttachmentById($attachmentId);

        if (is_null($attachment)) {
            $this->app->halt(404, json_encode("Attachment with the specified ID was not found"));
        }

        if ($activity->getId() !== $attachment->getActivity()->getId()) {
            $this->app->halt(404, json_encode("Attachment ID and activity ID do not match"));
        }

        $location = $this->app->config['uploadsDirectory'] . '/' . $attachment->getLocation();

        if (file_exists($location)) {
            $this->app->response->setStatus(200);
            $this->app->response->headers->set('Content-Type', 'application/octet-stream');
            $this->app->response->body(readfile($location));
        }
    }

    /**
     * Removes the activity with the specified ID
     * Outputs a 404 status if the activity with the specified id was not found.
     *
     * @param $id.
     *
     */
    public function deleteAction($id) {
        // TODO check if allowed to remove
        // TODO also delete sub-entities
        $activity = $this->getActivityMapper()->findActivityById($id);

        if (is_null($activity)) {
            $this->app->halt(404, json_encode("Activity with the specified ID was not found"));
        }

        $this->getActivityMapper()->remove($activity);
        $this->getActivityMapper()->flush();
        $this->app->response->setStatus(204);
    }

    /**
     * Get the Attachment service.
     *
     * @return \Service\AttachmentService
     */
    protected function getAttachmentService() {
        return $this->app->service_attachment;
    }

    /**
     * Get the Activity service.
     *
     * @return \Service\ActivityService
     */
    protected function getActivityService() {
        return $this->app->service_activity;
    }

    /**
     * Get the activity mapper.
     *
     * @return \Mapper\Activity
     */
    protected function getActivityMapper() {
        return $this->app->mapper_activity;
    }

    /**
     * Get the attachment mapper.
     *
     * @return \Mapper\Attachment
     */
    protected function getAttachmentMapper() {
        return $this->app->mapper_attachment;
    }

}