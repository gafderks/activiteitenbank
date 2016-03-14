<?php

namespace Service;

/**
 * Class RatingService
 *
 * Provides methods dealing with Ratings.
 *
 * @package Service
 */
class RatingService extends Service
{

    /**
     * Returns whether the specified user is allowed to rate activities.
     *
     * @param \Model\User|null $user
     * @return bool
     */
    public function userMayRate(\Model\User $user = null) {
        if ($user === null) {
            // no user is defined
            $role = new \Model\Enum\UserRole(\Model\Enum\UserRole::Guest);
            if (!$this->container['acl']->isAllowed($role->value(),
                'rating')) {
                return false;
            }
        } else {
            // check if user is allowed to perform the operation on an activity that is not its own
            if (!$this->container['acl']->isAllowed($user->getRole()->value(),
                'rating')) {
                return false;
            }
        }
        return true; // user is allowed to perform operation
    }

    /**
     * Returns whether the specified token is allowed to rate activities.
     *
     * @param object $token JSON web token
     * @return bool allowed
     */
    public function tokenMayRate($token) {
        $user = $this->getJwtService()->getUser($token);
        // check if allowed according to the scope of the token
        if (!$this->getJwtService()->tokenIsAllowed($token, 'rating')) {
            return false;
        }
        return true; // user is allowed to perform operation
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