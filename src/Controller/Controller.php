<?php
// /src/Controller/Controller.php

namespace Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;

/**
 * Class Controller
 * Abstract class for controllers.
 *
 * @package Controller
 */
abstract class Controller
{
    protected $container;

    /**
     * Controller constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * Creates a Response for returning an exception.
     *
     * @param Response   $response
     * @param \Exception $exception
     * @param int        $status HTTP output status
     * @return Response  response object with the specified status and the message from the exception
     */
    protected function getExceptionResponse(Response $response, \Exception $exception, $status = 400) {
        $response = $response->withStatus($status);
        $response->getBody()->write($exception->getMessage());
        return $response;
    }

    /**
     * Creates a Response for returning an Object in JSON format.
     *
     * @param Response $response
     * @param          $object
     * @param int      $status HTTP output status
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    protected function getJsonResponse(Response $response, $object, $status = 200) {
        $response = $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($object));
        return $response;
    }

    /**
     * Creates a Response for redirecting to a named route.
     *
     * @param Response $response
     * @param          $routeName
     * @param int      $status HTTP output status
     * @return \Psr\Http\Message\MessageInterface
     */
    protected function getRedirectResponse(Response $response, $routeName, $status = 301) {
        return $response->withStatus($status)->withHeader('Location', $this->container['router']->pathFor($routeName));
    }

}