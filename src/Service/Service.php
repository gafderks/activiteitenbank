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
    protected $container;

    /**
     * Service constructor.
     */
    function __construct($container) {
        $this->container = $container;
    }

}