<?php
// /router.php


$app->get('/hello/:name', function ($name) use ($app) {
$params = array('name' => $name);
$app->render('pages/hello.html', $params);
});

/**
 * Login Routes
 */
$app->get('/login', '\Controller\LoginController:indexAction');
$app->post('/login', '\Controller\LoginController:postAction');

/**
 * Explore Routes
 */
$app->get('/explore', '\Controller\ExplorerController:indexAction');