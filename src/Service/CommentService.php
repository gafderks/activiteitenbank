<?php

namespace Service;

/**
 * Class CommentService
 *
 * Provides methods dealing with Comments.
 *
 * @package Service
 */
class CommentService extends Service
{

    /**
     * Returns whether the specified user is allowed to comment activities.
     *
     * @param \Model\User|null $user
     * @return bool
     */
    public function userMayComment(\Model\User $user = null) {
        if ($user === null) {
            // no user is defined
            $role = new \Model\Enum\UserRole(\Model\Enum\UserRole::Guest);
            if (!$this->container['acl']->isAllowed($role->value(),
                'comment', 'create')) {
                return false;
            }
        } else {
            // check if user is allowed to perform the operation on an activity that is not its own
            if (!$this->container['acl']->isAllowed($user->getRole()->value(),
                'comment', 'create')) {
                return false;
            }
        }
        return true; // user is allowed to perform operation
    }

    /**
     * Returns whether the specified token is allowed to comment activities.
     *
     * @param object $token JSON web token
     * @return bool allowed
     */
    public function tokenMayComment($token) {
        $user = $this->getJwtService()->getUser($token);
        // check if allowed according to the scope of the token
        if (!$this->getJwtService()->tokenIsAllowed($token, 'comment', 'create')) {
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