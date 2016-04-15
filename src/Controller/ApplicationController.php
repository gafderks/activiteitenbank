<?php
// /src/Controller/ApplicationController.php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class ApplicationController
 *
 * @package Controller
 */
class ApplicationController extends Controller
{

    /**
     * Returns a JS file with the translations of the strings as defined in the Translate view-helper.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function TranslatorAction(Request $request, Response $response, $args = []) {
//        $dictionary = new \View\Helper\Dictionary();
//        $translations = $dictionary->getTranslations();
//        return $this->getJsonResponse($response, $translations, 200);
        $params = [

        ];
        $this->container['view']->render($response, 'js/translator.twig', $params);
        return $response->withHeader('Content-Type', 'application/javascript');
    }

}