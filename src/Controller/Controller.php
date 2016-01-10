<?php
// /src/Controller/Controller.php

namespace Controller;


abstract class Controller
{
    protected $app;

    function __construct() {
        $this->app = \Slim\Slim::getInstance();
    }

}