<?php
// /src/Service/Service.php

namespace Service;

/**
 * Class Service
 * Abstract class for services.
 *
 * @package Service
 */
abstract class Service
{
    protected $app;

    /**
     * Service constructor.
     */
    function __construct() {
        $this->app = \Slim\Slim::getInstance();
    }

}