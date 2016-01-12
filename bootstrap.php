<?php
// /bootstrap.php

use Doctrine\ORM\Tools\Setup;

// load required files
require_once "vendor/autoload.php";
$config = include("config.php");

// create autoloader
function __autoload($className) {
    include __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', '/', $className) . '.php';
}
spl_autoload_register('__autoload');

\Slim\Slim::registerAutoloader();

// absolute path to the root directory
if ( !defined('ABSPATH') )
    define('ABSPATH', $config['absolutePath']);

// create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$doctrineConfig = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src"), $isDevMode);

// database configuration parameters
$conn = $config['dbConnectionParams'];

// obtaining the entity manager
static $entityManager;
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $doctrineConfig);

$loader = new \Twig_Loader_Filesystem("view/{$config['template']}/templates");
$twig   = new \Twig_Environment($loader);
//$twig->addExtension(new \Twig_Extensions_Extension_I18n());

// start session
session_cache_limiter(false);
session_start();

// start Slim
static $app;
$app = new \Slim\Slim([
    'debug'          => true,
    'templates.path' => "view/{$config['template']}/templates",
//    'locales.path'   => "public/assets/{$config['template']}/locales"
]);
// define the engine used for the view @see http://twig.sensiolabs.org
$app->view = new \Slim\Views\Twig();
$app->view->setTemplatesDirectory("view/{$config['template']}/templates");

// twig extensions
$app->view()->parserExtensions = array(
    'Twig_Extension_Debug',
//    'Twig_Extensions_Extension_I18n'
);

// Twig configuration
$view = $app->view();
$view->parserOptions = ['debug' => true];
$view->parserExtensions = [new \Slim\Views\TwigExtension()];
// register template assets url in view
$app->hook('slim.before', function () use ($app, $config) {
    $app->view()->appendData(array(
        'baseUrl' => $config['baseUrl'],
        'assetsUrl' => "{$config['baseUrl']}/public/assets/{$config['template']}",
    ));
});

// language configuration
//$locality = 'en_US'; // locality should be determined here
//putenv("LC_ALL={$locality}"); // windows
//setlocale(LC_ALL, $locality); // Linux

//if (false === function_exists('gettext')) {
//    throw new \Exception("You do not have the gettext library installed with PHP.");
//}
///**
// * Because the .po file is named messages.po, the text domain must be named
// * that as well. The second parameter is the base directory to start
// * searching in.
// */
//bindtextdomain('default', ABSPATH . "public/assets/{$config['template']}/locale");
//// Tell the application to use this text domain, or messages.mo.
//textdomain('default');

// load application configuration
$applicationConfig = include("config/config.php");

// load routes
foreach ($applicationConfig['router']['routes'] as $name => $route) {
    switch($route['type']) {
        case 'literal':
            $controller = $route['options']['controller'];
            $action = $route['options']['action'];

            switch($route['method']) {
                case 'get':
                    $app->get(
                        $route['options']['route'],
                        "\\Controller\\{$controller}:{$action}Action"
                    );
                    break;
                case 'post':
                    $app->post(
                        $route['options']['route'],
                        "\\Controller\\{$controller}:{$action}Action"
                    );
                    break;
            }
            break;
    }
}

// dependency injection
foreach ($applicationConfig['resources'] as $name => $resource) {
    switch($resource['type']) {
        case 'service':
            $class = '\Service\\'.$resource['service'];
            $app->container->singleton("service_{$name}", function() use ($class) {
              return new $class();
            });
            break;
        case 'mapper':
            $class = '\Mapper\\'.$resource['mapper'];
            $app->container->singleton("mapper_{$name}", function() use ($class, $entityManager) {
                return new $class($entityManager);
            });
            break;
    }
}