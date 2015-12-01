<?php
// /src/Controller/Controller.php

namespace Controller;


class Controller
{
    protected $app;

    function __construct() {
        $this->app = \Slim\Slim::getInstance();
    }

}