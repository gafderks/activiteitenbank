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
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function uploadAction(Request $request, Response $response, $args = []) {
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['activityId']);

            // if token is not allowed to update this activity, output 401
            if (!$this->getActivityService()->tokenMayEdit($activity, $this->container['jwt'])) {
                return $this->getExceptionResponse($response,
                    new \Exception("You are not allowed to perform this action"), 401);
            }

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
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function downloadAction(Request $request, Response $response, $args = []) {
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['activityId']);
            $attachment = $this->getAttachmentMapper()->findAttachmentById($args['attachmentId']);

            // if token is not allowed to view this activity, output 401
            if (!$this->getActivityService()->tokenMayView($activity, $this->container['jwt'])) {
                return $this->getExceptionResponse($response,
                    new \Exception("You are not allowed to perform this action"), 401);
            }

            if ($activity->getId() !== $attachment->getActivity()->getId()) {
                throw new \Exception('Attachment ID and activity ID do not match');
            }

            // obtain file path
            $path = $attachment->getPath();

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
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function deleteAction(Request $request, Response $response, $args = []) {
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['activityId']);
            $attachment = $this->getAttachmentMapper()->findAttachmentById($args['attachmentId']);

            // if token is not allowed to update this activity, output 401
            if (!$this->getActivityService()->tokenMayEdit($activity, $this->container['jwt'])) {
                return $this->getExceptionResponse($response,
                    new \Exception("You are not allowed to perform this action"), 401);
            }

            if ($activity->getId() !== $attachment->getActivity()->getId()) {
                throw new \Exception('Attachment ID and activity ID do not match');
            }

            // remove attachment file
            unlink($attachment->getPath());

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
        return $this->container['service_attachment'];
    }

    /**
     * Get the Activity service.
     *
     * @return \Service\ActivityService
     */
    protected function getActivityService() {
        return $this->container['service_activity'];
    }

    /**
     * Get the activity mapper.
     *
     * @return \Mapper\Activity
     */
    protected function getActivityMapper() {
        return $this->container['mapper_activity'];
    }

    /**
     * Get the attachment mapper.
     *
     * @return \Mapper\Attachment
     */
    protected function getAttachmentMapper() {
        return $this->container['mapper_attachment'];
    }

}