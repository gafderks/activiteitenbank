<?php

namespace Acl;

/**
 * Class Scope
 *
 * @package Acl
 */
class Scope
{

    private $scope = [];

    /**
     * Scope constructor.
     */
    public function __construct(array $scope) {
        $acl = new \Acl\Acl();
        foreach ($scope as $resource => $privileges) {
            if (!$acl->hasResource($resource)) {
                throw new \Exception("No resource $resource was found");
            } else {
                $this->scope[$resource] = ["actions" => $privileges];
            }
        }
    }

    /**
     * Returns the scope as an array.
     *
     * @return array
     */
    public function toArray() {
        return $this->scope;
    }

}