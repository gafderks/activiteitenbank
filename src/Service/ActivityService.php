<?php

namespace Service;

/**
 * Class ActivityService
 *
 * Provides methods dealing with Activities.
 *
 * @package Service
 */
class ActivityService extends Service
{

    /**
     * Generates a URL-friendly version of a string.
     *
     * @param string $name input string
     * @return string URL-version friendly version of $name
     */
    public function generateSlug($name) {
        $name = strtolower($name); // convert to lower case
        $name = preg_replace('/[^\w ]+/', '', $name); // remove illegal characters
        $name = preg_replace('/\s+/', '-', $name); // replace spaces
        return $name;
    }

    /**
     * Placeholder method. Sanitizing takes place at output.
     *
     * @param $elaboration
     * @return mixed
     */
    public function sanitizeElaboration($elaboration) {
        //$elaboration = str_replace(['<', '>'], ['&lt;', '&gt;'], $elaboration);
        return $elaboration;
    }

    /**
     * Sets the supplied activity according to the supplied object.
     * This method does NOT do input validation, so it is important to setup the ValidatorService as middleware.
     *
     * @param \Model\Activity\Activity $activity
     * @param                          $definition
     * @return \Model\Activity\Activity
     */
    public function setActivityFromObject(\Model\Activity\Activity $activity, $definition) {
        $input = $definition;

        // set flat properties
        $activity->setName($input->title);
        $activity->setSlug($this->generateSlug($activity->getName()));
        $activity->setElaboration($this->sanitizeElaboration($input->elaboration));
        $activity->setDifficulty(new \Model\Enum\Level($input->difficulty));
        $activity->setGuidance(new \Model\Enum\Level($input->guidance));
        $activity->setMotivation(new \Model\Enum\Level($input->motivation));
        $activity->setGroupSizeMin($input->groupSizeMin);
        $activity->setGroupSizeMax($input->groupSizeMax);
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

        return $activity;
    }

    /**
     * Returns whether the specified user is allowed to download the specified activity.
     *
     * @param \Model\User              $user
     * @param \Model\Activity\Activity $activity
     * @return bool allowed
     */
    public function userMayDownload(\Model\Activity\Activity $activity, \Model\User $user = null) {
        return $this->userMay($activity, 'download', $user);
    }

    /**
     * Returns whether the specified user is allowed to delete the specified activity.
     *
     * @param \Model\User              $user
     * @param \Model\Activity\Activity $activity
     * @return bool allowed
     */
    public function userMayDelete(\Model\Activity\Activity $activity, \Model\User $user = null) {
        return $this->userMay($activity, 'delete', $user);
    }

    /**
     * Returns whether the specified user is allowed to view the specified activity.
     *
     * @param \Model\User              $user
     * @param \Model\Activity\Activity $activity
     * @return bool allowed
     */
    public function userMayView(\Model\Activity\Activity $activity, \Model\User $user = null) {
        return $this->userMay($activity, 'view', $user);
    }

    /**
     * Returns whether the specified user is allowed to edit the specified activity.
     *
     * @param \Model\User              $user
     * @param \Model\Activity\Activity $activity
     * @return bool allowed
     */
    public function userMayEdit(\Model\Activity\Activity $activity, \Model\User $user =  null) {
        return $this->userMay($activity, 'edit', $user);
    }

    /**
     * Returns whether the specified user is allowed to perform the specified operation on the specified activity.
     *
     * @param \Model\User              $user
     * @param \Model\Activity\Activity $activity
     * @param string                   $operation
     * @return bool allowed
     */
    private function userMay(\Model\Activity\Activity $activity, $operation, \Model\User $user = null) {
        if ($user === null) {
            // no user is defined
            $role = new \Model\Enum\UserRole(\Model\Enum\UserRole::Guest);
            if (!$this->container['acl']->isAllowed($role->value(),
                'activity', $operation)) {
                return false;
            }
        } elseif ($activity->getCreator()->getId() === $user->getId()) {
            // check if user is allowed to perform the operation on its own activity
            if (!$this->container['acl']->isAllowed($user->getRole()->value(),
                'ownActivity', $operation)) {
                return false;
            }
        } else {
            // check if user is allowed to perform the operation on an activity that is not its own
            if (!$this->container['acl']->isAllowed($user->getRole()->value(),
                'activity', $operation)) {
                return false;
            }
        }
        return true; // user is allowed to perform operation
    }

    /**
     * Returns whether the specified token is allowed to download the specified activity.
     *
     * @param \Model\Activity\Activity $activity
     * @param object                   $token JSON web token
     * @return bool allowed
     */
    public function tokenMayDownload(\Model\Activity\Activity $activity, $token) {
        return $this->tokenMay($activity, 'download', $token);
    }

    /**
     * Returns whether the specified token is allowed to delete the specified activity.
     *
     * @param \Model\Activity\Activity $activity
     * @param object                   $token JSON web token
     * @return bool allowed
     */
    public function tokenMayDelete(\Model\Activity\Activity $activity, $token) {
        return $this->tokenMay($activity, 'delete', $token);
    }

    /**
     * Returns whether the specified token is allowed to view the specified activity.
     *
     * @param \Model\Activity\Activity $activity
     * @param object                   $token JSON web token
     * @return bool allowed
     */
    public function tokenMayView(\Model\Activity\Activity $activity, $token) {
        return $this->tokenMay($activity, 'view', $token);
    }

    /**
     * Returns whether the specified token is allowed to edit the specified activity.
     *
     * @param \Model\Activity\Activity $activity
     * @param object                   $token JSON web token
     * @return bool allowed
     */
    public function tokenMayEdit(\Model\Activity\Activity $activity, $token) {
        return $this->tokenMay($activity, 'edit', $token);
    }

    /**
     * Returns whether the specified token is allowed to perform the specified operation on the specified activity.
     *
     * @param \Model\Activity\Activity $activity
     * @param string                   $operation
     * @param object                   $token JSON web token
     * @return bool
     */
    private function tokenMay(\Model\Activity\Activity $activity, $operation, $token) {
        $user = $this->getJwtService()->getUser($token);
        // check if user is allowed to perform the operation on its own activity
        if (!$this->userMay($activity, $operation, $user)) {
            return false;
        }
        if ($activity->getCreator()->getId() === $user->getId()) {
            // check if allowed according to the scope of the token
            if (!$this->getJwtService()->tokenIsAllowed($token, 'ownActivity', $operation)) {
                return false;
            }
        } else {
            // check if allowed according to the scope of the token
            if (!$this->getJwtService()->tokenIsAllowed($token, 'activity', $operation)) {
                return false;
            }
        }
        return true; // user is allowed to perform operation
    }

    /**
     * Returns whether the specified token is allowed to create a new activity.
     *
     * @param object $token JSON web token
     * @return bool allowed
     */
    public function tokenMayCreate($token) {
        $user = $this->getJwtService()->getUser($token);
        // check if token is allowed to perform the operation on its own activity
        if (!$this->userMayCreate($user)) {
            return false;
        }

        // check if allowed according to the scope of the token
        if (!$this->getJwtService()->tokenIsAllowed($token, 'activity', 'create')) {
            return false;
        }
        return true; // token is allowed to perform operation
    }

    /**
     * Returns whether the specified user is allowed to create a new activity.
     *
     * @param \Model\User              $user
     * @return bool allowed
     */
    public function userMayCreate(\Model\User $user = null) {
        // check if user is allowed to create a new activity
        if ($user === null) {
            // no user is defined
            $role = new \Model\Enum\UserRole(\Model\Enum\UserRole::Guest);
            if (!$this->container['acl']->isAllowed($role->value(),
                'activity', 'create')) {
                return false;
            }
        }
        if (!$this->container['acl']->isAllowed($user->getRole()->value(),
            'activity', 'create')) {
            return false;
        }
        return true; // user is allowed to create
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
     * Get the login service.
     *
     * @return \Service\LoginService
     */
    protected function getLoginService() {
        return $this->container['service_login'];
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