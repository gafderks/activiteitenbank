<?php
// /public/index.php

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

require_once "bootstrap.php";

$app->run();