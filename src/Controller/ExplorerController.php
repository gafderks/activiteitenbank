<?php
// /src/Controller/ExplorerController.php

namespace Controller;


class ExplorerController extends Controller
{

    private $searchColumns = [
        //['Name', visible, searchable]
        ['name' => 'Name', 'visible' => true, 'searchable' => true],
        ['name' => 'Category', 'visible' => true, 'searchable' => true],
        ['name' => 'Duration', 'visible' => true, 'searchable' => true],
        ['name' => 'Budget', 'visible' => true, 'searchable' => true],
        ['name' => 'Difficulty', 'visible' => false, 'searchable' => true],
        ['name' => 'Guidance', 'visible' => false, 'searchable' => true],
        ['name' => 'Motivation', 'visible' => false, 'searchable' => true],
        ['name' => 'Activity areas', 'visible' => false, 'searchable' => true],
        ['name' => 'Suitable groups', 'visible' => false, 'searchable' => true],
        ['name' => 'Creator', 'visible' => false, 'searchable' => true],
    ];

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