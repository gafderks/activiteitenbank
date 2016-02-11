<?php
// /src/Controller/ActivityController.php

namespace Controller;

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

    public function updateAction() {
        throw new \Exception("Not implemented");
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

        // set flat properties
        $activity->setName($input->title);
        $activity->setSlug($this->getActivityService()->generateSlug($activity->getName()));
        $activity->setElaboration($this->getActivityService()->sanitizeElaboration($input->elaboration));
        $activity->setDifficulty(new \Model\Enum\Level($input->difficulty));
        $activity->setGuidance(new \Model\Enum\Level($input->guidance));
        $activity->setMotivation(new \Model\Enum\Level($input->motivation));
        $activity->setActivityAreas($input->activityAreas);
        $activity->setSuitable_groups($input->groups);

        // set planning
        $planning = new \Model\Activity\Planning\Planning();
        $position = 0;
        foreach($input->planning as $planningAction) {
            $action = new \Model\Activity\Planning\Action();
            $action->setPlanning($planning);
            $action->setDescription($planningAction->description);
            $action->setTimeSpan($planningAction->duration);
            $action->setPosition($position);
            $this->getActivityMapper()->persist($action);
            $position++;
        }
        $activity->setPlanning($planning);
        $this->getActivityMapper()->persist($planning);

        // set checklist
        $checklist = new \Model\Activity\Checklist\Checklist();
        $position = 0;
        foreach($input->checklist as $checklistItem) {
            $item = new \Model\Activity\Checklist\ChecklistItem();
            $item->setChecklist($checklist);
            $item->setDescription($checklistItem);
            $item->setPosition($position);
            $this->getActivityMapper()->persist($item);
            $position++;
        }
        $activity->setChecklist($checklist);
        $this->getActivityMapper()->persist($checklist);

        // set material list
        $materialList = new \Model\Activity\MaterialList\MaterialList();
        $position = 0;
        foreach($input->materials as $materialItem) {
            $item = new \Model\Activity\MaterialList\MaterialListItem();
            $item->setMaterialList($materialList);
            $item->setAmount($materialItem->amount);
            $item->setDescription($materialItem->description);
            $item->setPosition($position);
            $this->getActivityMapper()->persist($item);
            $position++;
        }
        $activity->setMaterials($materialList);
        $this->getActivityMapper()->persist($materialList);

        // set budgetary
        $budgetary = new \Model\Activity\Budget\Budget();
        $position = 0;
        foreach($input->budget as $budgetItem) {
            $item = new \Model\Activity\Budget\Expense();
            $item->setBudget($budgetary);
            $item->setAmount($budgetItem->amount);
            $item->setDescription($budgetItem->description);
            $item->setCost($budgetItem->cost);
            $item->setPosition($position);
            $this->getActivityMapper()->persist($item);
            $position++;
        }
        $activity->setBudget($budgetary);
        $this->getActivityMapper()->persist($budgetary);

        /* Attachments are uploaded via separate requests */

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
     * Get the activity mapper.
     *
     * @return \Mapper\Activity
     */
    protected function getActivityMapper() {
        return $this->app->mapper_activity;
    }

}