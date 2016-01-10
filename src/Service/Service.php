<?php
// /src/Service/Service.php

namespace Service;


abstract class Service
{
    protected $app;

    function __construct() {
        $this->app = \Slim\Slim::getInstance();
    }

}