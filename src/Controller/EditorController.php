<?php
// /src/Controller/EditorController.php

namespace Controller;

/**
 * Class EditorController
 *
 * @package Controller
 */
class EditorController extends Controller
{

    public function newAction() {
        $params = [

        ];
        $this->app->render('pages/editor.html', $params);
    }

}