<?php
// /src/Controller/AttachmentController.php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
    public function uploadAction(Request $request, Response $response, $args = []) {
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['activityId']);

            $attachment = $this->getAttachmentService()->uploadAttachment('file');
            $attachment->setActivity($activity);
            // store attachment in database
            $this->getActivityMapper()->persist($attachment);
            $this->getActivityMapper()->flush();

            return $this->getJsonResponse($response, $attachment, 201);
        } catch (\Exception $exception) {
            return $this->getExceptionResponse($response, $exception, 404);
        }
    }

    /**
     * Returns the file that is located at the activity with the specified id and the attachment with the specified id.
     * Outputs 200 on success. Outputs 400 on failure.
     *
     * @param $activityId integer
     * @param $attachmentId integer
     */
    public function downloadAction(Request $request, Response $response, $args = []) {
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['activityId']);
            $attachment = $this->getAttachmentMapper()->findAttachmentById($args['attachmentId']);

            if ($activity->getId() !== $attachment->getActivity()->getId()) {
                throw new \Exception('Attachment ID and activity ID do not match');
            }

            // obtain file path
            $path = $attachment->getPath($this->container);

            // determine mime type
            $mimeType = mime_content_type($path);

            if (file_exists($path)) {
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-Type', $mimeType)
                    ->withHeader('Content-Disposition', 'attachment; filename="'.
                        $attachment->getName().'"')
                    ->withHeader('Content-Length', filesize($path));
                readfile($path);
                return $response;
            } else {
                throw new \Exception('File does not exist');
            }
        } catch (\Exception $exception) {
            return $this->getExceptionResponse($response, $exception, 404);
        }
    }

    /**
     * Removes the attachment with the specified ID and activity ID
     * Outputs a 404 status if the attachment was not found.
     *
     * @param $activityId integer
     * @param $attachmentId integer
     */
    public function deleteAction(Request $request, Response $response, $args = []) {
        // TODO check if allowed to remove
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['activityId']);
            $attachment = $this->getAttachmentMapper()->findAttachmentById($args['attachmentId']);

            if ($activity->getId() !== $attachment->getActivity()->getId()) {
                throw new \Exception('Attachment ID and activity ID do not match');
            }

            // remove attachment file
            unlink($attachment->getPath($this->container));

            // remove attachment from database
            $this->getAttachmentMapper()->remove($attachment);
            $this->getAttachmentMapper()->flush();

            return $response->withStatus(204);
        } catch (\Exception $exception) {
            return $this->getExceptionResponse($response, $exception, 404);
        }
    }

    /**
     * Get the Attachment service.
     *
     * @return \Service\AttachmentService
     */
    protected function getAttachmentService() {
        return $this->container->service_attachment;
    }

    /**
     * Get the Activity service.
     *
     * @return \Service\ActivityService
     */
    protected function getActivityService() {
        return $this->container->service_activity;
    }

    /**
     * Get the activity mapper.
     *
     * @return \Mapper\Activity
     */
    protected function getActivityMapper() {
        return $this->container->mapper_activity;
    }

    /**
     * Get the attachment mapper.
     *
     * @return \Mapper\Attachment
     */
    protected function getAttachmentMapper() {
        return $this->container->mapper_attachment;
    }

}