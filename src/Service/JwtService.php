<?php

namespace Service;

use \Firebase\JWT\JWT;

/**
 * Class JwtService
 *
 * Provides methods for dealing with JSON Web Tokens files.
 * THIS CLASS MUST WORK WITHOUT AN ACTIVE SESSION
 *
 * @see https://tools.ietf.org/html/rfc7519
 *
 * @package Service
 */
class JwtService extends Service
{

    /**
     * @param \Model\User $user
     * @param \Acl\Scope  $scope
     * @param array       $options
     * @return string JSON web token
     */
    public function generateToken(\Model\User $user,  \Acl\Scope $scope, $options = []) {
        $token = array_merge([
            'iss' => $this->container['config']['domain'],
            'iat' => time(),
            'exp' => time() + (24 * 60 * 60), // tomorrow
            'sub' => $user->getId(),
            'scopes' => $scope->toArray(),
        ], $options);
        $jwt = JWT::encode($token, $this->container['config']['apiSecret']);
        return $jwt;
    }

    /**
     * Returns whether the privileges in the token are a subset of the privileges in the ACL for the user role.
     *
     * @param object $token JSON web token
     * @return bool if user is allowed all privileges in the token
     */
    public function authorizeToken($token) {
        $acl = new \Acl\Acl();
        $user = $this->getUserService()->findUser($token->sub);
        $role = $user->getRole()->value();
        foreach ($token->scopes as $resource => $options) {
            foreach ($options as $option => $actions) {
                if ($actions === null) {
                    if (!$acl->isAllowed($role, $resource)) {
                        return false;
                    }
                } else {
                    foreach ($actions as $action => $privilege) {
                        if (!$acl->isAllowed($role, $resource, $privilege)) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * Returns whether the token is allowed to perform the specified privilege on the specified resource.
     * This function checks whether the combination of resource and privilege is present in the token.
     *
     * @param object $token JSON web token
     * @param string $resource
     * @param string $privilege
     * @return bool token is allowed to perform the specified privilege on the specified resource
     */
    public function tokenIsAllowed($token, $resource, $privilege) {
        // search for the combination of resource and privilege
        if (!isset($token->scopes->$resource)) {
            return false; // resource is not present in token
        }
        if (!in_array($privilege, $token->scopes->$resource->actions)) {
            return false;
        }
        return true;
    }

    /**
     * Returns the User that is associated with the token.
     *
     * @param object $token JSON web token
     * @return \Model\User|null
     */
    public function getUser($token) {
        return $this->getUserService()->findUser($token->sub);
    }

    /**
     * @return \Service\UserService
     */
    public function getUserService() {
        return $this->container['service_user'];
    }

}