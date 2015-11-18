<?php
// /bootstrap.php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// load required files
require_once "vendor/autoload.php";
require_once "config.php";

// create autoloader
function __autoload($className) {
    include __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', '/', $className) . '.php';
}
spl_autoload_register('__autoload');

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$doctrineConfig = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src"), $isDevMode);

// database configuration parameters
//$conn = [
//    'url' => getenv('ACTIVITYBANK_DB_URL'),
//    'driver' => 'pdo_pgsql'
//];
$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite',
);

// obtaining the entity manager
static $entityManager;
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $doctrineConfig);

// start Slim
static $app;
$app = new \Slim\Slim();

// load Routers
//$routerInitializer = new \Routers\Initializer($app, $entityManager);
//$routerInitializer->enableRouters($config['Routers']);
