<?php
// /src/Controller/Controller.php

namespace Controller;

/**
 * Class Controller
 * Abstract class for controllers.
 *
 * @package Controller
 */
abstract class Controller
{
    protected $app;

    /**
     * Controller constructor.
     */
    function __construct() {
        $this->app = \Slim\Slim::getInstance();
    }

}