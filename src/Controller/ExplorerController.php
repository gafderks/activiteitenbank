<?php
// /src/Controller/ExplorerController.php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class ExplorerController
 *
 * @package Controller
 */
class ExplorerController extends Controller
{

    private $searchColumns = [
        //['Name', visible, searchable]
        ['name' => 'Name', 'visible' => true, 'searchable' => true, 'orderable' => true],
        ['name' => 'Category', 'visible' => true, 'searchable' => true, 'orderable' => false],
        ['name' => 'Duration', 'visible' => true, 'searchable' => true, 'orderable' => true],
        ['name' => 'Budget', 'visible' => true, 'searchable' => true, 'orderable' => true],
        ['name' => 'Difficulty', 'visible' => false, 'searchable' => true, 'orderable' => true],
        ['name' => 'Guidance', 'visible' => false, 'searchable' => true, 'orderable' => true],
        ['name' => 'Motivation', 'visible' => false, 'searchable' => true, 'orderable' => true],
        ['name' => 'Group size', 'visible' => false, 'searchable' => true, 'orderable' => false],
        ['name' => 'Activity areas', 'visible' => false, 'searchable' => true, 'orderable' => false],
        ['name' => 'Suitable groups', 'visible' => false, 'searchable' => true, 'orderable' => false],
        ['name' => 'Creator', 'visible' => false, 'searchable' => true, 'orderable' => true],
    ];

    /**
     * Shows the explorer.
     */
    public function indexAction(Request $request, Response $response, $args = []) {
        $params = [
            'searchColumns' => $this->searchColumns,
            'activities' => $this->getActivityMapper()->findAll()
        ];
        $this->container->view->render($response, 'pages/explorer.twig', $params);
        return $response;
    }

    /**
     * Get the activity mapper.
     *
     * @return \Mapper\Activity
     */
    protected function getActivityMapper() {
        return $this->container->mapper_activity;
    }

}