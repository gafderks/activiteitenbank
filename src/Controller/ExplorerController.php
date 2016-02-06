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
        ['name' => 'Creator', 'visible' => true, 'searchable' => true],
    ];

    public function indexAction() {
        $params = [
            'searchColumns' => $this->searchColumns
        ];
        $this->app->render('pages/explorer.twig', $params);
    }

}