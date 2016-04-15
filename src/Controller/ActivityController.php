<?php
// /src/Controller/ActivityController.php

namespace Controller;

use Knp\Snappy\Pdf;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Exceptions\NullTypeException;

/**
 * Class ActivityController
 *
 * @package Controller
 */
class ActivityController extends Controller
{

    /**
     * Looks up the details of an activity and returns them if found.
     * Outputs a 404 status if the activity with the specified id was not found.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function getAction(Request $request, Response $response, $args = []) {
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['id']);

            if (!$this->container['acl']->isAllowed('guest', 'activity', 'view')) {
                // if token is not allowed to view this activity, output 401
                if (!$this->getActivityService()->tokenMayView($activity, $this->container['jwt'])) {
                    return $this->getExceptionResponse($response,
                        new \Exception("You are not allowed to perform this action"), 401);
                }
            }

            return $this->getJsonResponse($response, $activity, 200);
        } catch(\Exception $exception) {
            return $this->getExceptionResponse($response, $exception, 404);
        }
    }

    /**
     * Generates a PDF file from the specified activity.
     * Outputs a 404 status if the activity with the specified id was not found.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function generatePdfAction(Request $request, Response $response, $args = []) {
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['id']);

            if (!$this->container['acl']->isAllowed('guest', 'activity', 'download')) {
                // if token is not allowed to download this activity, output 401
                if (!$this->getActivityService()->tokenMayDownload($activity, $this->container['jwt'])) {
                    return $this->getExceptionResponse($response,
                        new \Exception("You are not allowed to perform this action"), 401);
                }
            }

            // generate the rendered html template
            $params = [
                'activity' => $activity,
            ];
            $renderedHtml = $this->container['view']->fetch('pdf/activity.twig', $params);

            // generate files for header and footer
            $partials = $this->getPdfService()->renderTemplatesToUrls([
                'header' => ['pdf/partials/header.twig', []],
                'footer' => ['pdf/partials/footer.twig', []],
            ]);

            // initialize Snappy
            $snappy = new Pdf($this->container['config']['absolutePath'] .
                '/vendor/wemersonjanuario/wkhtmltopdf-windows/bin/32bit/wkhtmltopdf.exe');
            $snappy->setOptions([
                'page-size' => 'A4',
                'title' => $activity->getName(),
                'orientation' => 'Portrait',
                'header-spacing' => 0,
                'margin-top' => '10mm',
                'margin-bottom' => '20mm',
                'margin-left' => '10mm',
                'margin-right' => '10mm',
                'footer-spacing' => 0,
                //'header-html' => $partials['header'], // really need to use a file here
                'footer-html' => $partials['footer'],
            ]);

            // render PDF file
            $pdf = $snappy->getOutputFromHtml($renderedHtml);

            // clean up temporary files
            $this->getPdfService()->garbageCollectRenders($partials);

            // show response
            $response = $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/pdf')
                ->withHeader('Content-Disposition',
                    'attachment; filename="'.$activity->getSlug().'.pdf"')
                ->write($pdf);
            return $response;
        } catch(\Exception $exception) {
            return $this->getExceptionResponse($response, $exception, 404);
        }
    }

    /**
     * Updates the specified activity according to the specified definition.
     * This method does NOT do input validation, so it is important to setup the ValidatorService as middleware.
     * Outputs a 404 status if the activity with the specified id was not found.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function updateAction(Request $request, Response $response, $args = []) {
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['id']);

            if (!$this->container['acl']->isAllowed('guest', 'activity', 'edit')) {
                // if token is not allowed to update this activity, output 401
                if (!$this->getActivityService()->tokenMayEdit($activity, $this->container['jwt'])) {
                    return $this->getExceptionResponse($response,
                        new \Exception("You are not allowed to perform this action"), 401);
                }
            }

            // load input
            $input = json_decode($request->getBody());

            // set up meta data
            $now = new \DateTime('now');
            $activity->setModified($now);

            // set the properties of the activity
            $activity = $this->getActivityService()->setActivityFromObject($activity, $input);

            // save the activity
            $this->getActivityMapper()->persist($activity);

            // apply all modifications
            $this->getActivityMapper()->flush();
            // forces the entity manager to reload the activity in the next line
            $this->getActivityMapper()->clear();
            $newActivity = $this->getActivityMapper()->findActivityById($activity->getId());

            // show response
            return $this->getJsonResponse($response, $newActivity, 202);
        } catch(\Exception $exception) {
            return $this->getExceptionResponse($response, $exception, 404);
        }
    }

    /**
     * Removes the activity with the specified ID
     * Outputs a 404 status if the activity with the specified id was not found.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function deleteAction(Request $request, Response $response, $args = []) {
        // TODO also delete sub-entities
        try {
            $activity = $this->getActivityMapper()->findActivityById($args['id']);

            if (!$this->container['acl']->isAllowed('guest', 'activity', 'delete')) {
                // if token is not allowed to delete this activity, output 401
                if (!$this->getActivityService()->tokenMayDelete($activity, $this->container['jwt'])) {
                    return $this->getExceptionResponse($response,
                        new \Exception("You are not allowed to perform this action"), 401);
                }
            }

            // actually remove activity
            $this->getActivityMapper()->remove($activity);
            $this->getActivityMapper()->flush();

            $response = $response
                ->withStatus(204);
            return $response;
        } catch(\Exception $exception) {
            return $this->getExceptionResponse($response, $exception, 404);
        }
    }

    /**
     * Creates a new activity according to the JSON object that is in the request body.
     * This method does NOT do input validation, so it is important to setup the ValidatorService as middleware.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function createAction(Request $request, Response $response, $args = []) {
        if (!$this->container['acl']->isAllowed('guest', 'activity', 'create')) {
            // if token is not allowed to create a new activity, output 401
            if (!$this->getActivityService()->tokenMayCreate($this->container['jwt'])) {
                return $this->getExceptionResponse($response,
                    new \Exception("You are not allowed to perform this action"), 401);
            }
        }

        // load input
        $input = json_decode($request->getBody());

        // create new activity object
        $activity = new \Model\Activity\Activity();

        // set up meta data
        $now = new \DateTime('now');
        $activity->setCreated($now);
        $activity->setModified($now);
        $activity->setCreator($this->getJwtService()->getUser($this->container['jwt']));

        // set the properties of the activity
        $activity = $this->getActivityService()->setActivityFromObject($activity, $input);

        // save the activity
        $this->getActivityMapper()->persist($activity);

        // apply all modifications
        $this->getActivityMapper()->flush();
        // forces the entity manager to reload the activity in the next line
        $this->getActivityMapper()->clear();
        $newActivity = $this->getActivityMapper()->findActivityById($activity->getId());

        // show response
        return $this->getJsonResponse($response, $newActivity, 201);
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
     * Get the PDF service.
     *
     * @return \Service\PdfService
     */
    protected function getPdfService() {
        return $this->container['service_pdf'];
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
     * Get the JWT service.
     *
     * @return \Service\JwtService
     */
    protected function getJwtService() {
        return $this->container['service_jwt'];
    }

}