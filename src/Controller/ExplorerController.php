<?php
// /src/Controller/ExplorerController.php

namespace Controller;

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
    public function indexAction() {
        $params = [
            'searchColumns' => $this->searchColumns,
            'activities' => $this->getActivityMapper()->findAll()
        ];
        $this->app->render('pages/explorer.twig', $params);
    }

    /**
     * Get the activity mapper.
     *
     * @return \Mapper\Activity
     */
    protected function getActivityMapper() {
        return $this->app->mapper_activity;
    }

}