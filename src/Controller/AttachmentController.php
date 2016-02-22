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

    /**
     * Uploads the file that is set at request parameter 'file' as attachment.
     * Outputs 201 on success. Outputs 400 on failure.
     *
     * @param $activityId integer id of the activity to add the assignment to.
     */
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

    /**
     * Returns the file that is located at the activity with the specified id and the attachment with the specified id.
     * Outputs 200 on success. Outputs 400 on failure.
     *
     * @param $activityId integer
     * @param $attachmentId integer
     */
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
            $this->app->halt(400, json_encode("Attachment ID and activity ID do not match"));
        }

        $path = $attachment->getPath();

        if (file_exists($path)) {
            $this->app->response->setStatus(200);
            $this->app->response->headers->set('Content-Type', 'application/octet-stream');
            $this->app->response->body(readfile($path));
        }
    }

    /**
     * Removes the attachment with the specified ID and activity ID
     * Outputs a 404 status if the attachment was not found.
     *
     * @param $activityId integer
     * @param $attachmentId integer
     */
    public function deleteAction($activityId, $attachmentId) {
        // TODO check if allowed to remove
        // TODO also delete sub-entities
        $activity = $this->getActivityMapper()->findActivityById($activityId);

        if (is_null($activity)) {
            $this->app->halt(404, json_encode("Activity with the specified ID was not found"));
        }

        $attachment = $this->getAttachmentMapper()->findAttachmentById($attachmentId);

        if (is_null($attachment)) {
            $this->app->halt(404, json_encode("Attachment with the specified ID was not found"));
        }

        if ($activity->getId() !== $attachment->getActivity()->getId()) {
            $this->app->halt(400, json_encode("Attachment ID and activity ID do not match"));
        }

        // remove attachment file
        unlink($attachment->getPath());

        // remove attachment from database
        $this->getAttachmentMapper()->remove($attachment);
        $this->getAttachmentMapper()->flush();

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