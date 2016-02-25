<?php
// /src/Controller/ActivityController.php

namespace Controller;
use Knp\Snappy\Pdf;

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
     * @param $id
     */
    public function getAction($id) {
        $activity = $this->getActivityMapper()->findActivityById($id);

        if (is_null($activity)) {
            $this->app->halt(404, json_encode("Activity with the specified ID was not found"));
        }

        $this->app->response->setStatus(200);
        $this->app->response->headers->set('Content-Type', 'application/json');
        $this->app->response->body(json_encode($activity));
    }

    public function generatePdfAction($id) {
        $activity = $this->getActivityMapper()->findActivityById($id);

        if (is_null($activity)) {
            $this->app->halt(404, json_encode("Activity with the specified ID was not found"));
        }

        // generate the rendered html template
        $params = [
            'activity' => $activity,
        ];
        $renderedHtml = $this->app->view->fetch('pdf/activity.twig', $params);

        $partials = $this->getPdfService()->renderTemplatesToUrls([
            'header' => ['pdf/partials/header.twig', []],
            'footer' => ['pdf/partials/footer.twig', []],
        ]);

        $snappy = new Pdf($this->app->config['absolutePath'] .
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
        $pdf = $snappy->getOutputFromHtml($renderedHtml);

        // clean up temporary files
        $this->getPdfService()->garbageCollectRenders($partials);

        // show response
        $this->app->response->setStatus(200);
        $this->app->response->headers->set('Content-Type', 'application/pdf');
        $this->app->response->headers->set('Content-Disposition', 'attachment; filename="'.$activity->getSlug().'.pdf"');
        $this->app->response->body($pdf);

    }

    public function updateAction($id) {
        // TODO check if allowed to update
        $activity = $this->getActivityMapper()->findActivityById($id);

        if (is_null($activity)) {
            $this->app->halt(404, json_encode("Activity with the specified ID was not found"));
        }

        // load input
        $input = json_decode($this->app->request->getBody());

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
        $this->app->response->setStatus(202);
        $this->app->response->headers->set('Content-Type', 'application/json');
        $this->app->response->body(json_encode($newActivity));
    }

    /**
     * Removes the activity with the specified ID
     * Outputs a 404 status if the activity with the specified id was not found.
     *
     * @param $id
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
     * Creates a new activity according to the JSON object that is in the request body.
     * This method does NOT do input validation, so it is important to setup the ValidatorService as middleware.
     *
     */
    public function createAction() {
        // load input
        $input = json_decode($this->app->request->getBody());

        // create new activity object
        $activity = new \Model\Activity\Activity();

        // set up meta data
        $now = new \DateTime('now');
        $activity->setCreated($now);
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
        $this->app->response->setStatus(201);
        $this->app->response->headers->set('Content-Type', 'application/json');
        $this->app->response->body(json_encode($newActivity));
    }

    /**
     * Get the Activity service.
     *
     * @return \Service\ActivityService
     */
    protected function getActivityService()
    {
        return $this->app->service_activity;
    }

    /**
     * Get the PDF service.
     *
     * @return \Service\PdfService
     */
    protected function getPdfService()
    {
        return $this->app->service_pdf;
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