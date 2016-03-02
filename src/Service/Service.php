<?php
// /src/Service/Service.php

namespace Service;

use \Interop\Container\ContainerInterface;

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
     *
     * @param ContainerInterface $container
     */
    function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

}