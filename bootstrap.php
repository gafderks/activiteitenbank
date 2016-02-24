<?php
// /bootstrap.php

use Doctrine\ORM\Tools\Setup;

/********************************************************************************
 * Load configuration
 *******************************************************************************/

$config = include("config.php");

/********************************************************************************
 * Set up autoloader
 *******************************************************************************/

// load required files
require_once "vendor/autoload.php";

/********************************************************************************
 * Set up Doctrine ORM
 *******************************************************************************/

// create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$doctrineConfig = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/src"], $isDevMode);

// database configuration parameters
$conn = $config['dbConnectionParams'];

// obtaining the entity manager
static $entityManager;
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $doctrineConfig);

/********************************************************************************
 * Start session
 *******************************************************************************/

// start session
session_cache_limiter(false);
session_start();

/********************************************************************************
 * Start Slim and Twig
 *******************************************************************************/

// start Slim
$app = new \Slim\Slim([
    'debug' => true,
]);
// define the engine used for the view @see http://twig.sensiolabs.org
$app->view = new \Slim\Views\Twig();
$app->view->setTemplatesDirectory("view/{$config['template']}/templates");

// Twig configuration
$view = $app->view();
$view->parserOptions = ['debug' => true];
// twig extensions
$view->parserExtensions = [
    new \Slim\Views\TwigExtension(),
    new \Twig_Extension_Debug(),
    new \Twig_Extensions_Extension_I18n(),
];
// register custom filters
$int2time = new Twig_SimpleFilter('int2time', ['\View\Format', 'int2Time']);
$float2euro = new Twig_SimpleFilter('float2euro', ['\View\Format', 'float2Euro']);
$bb2html = new Twig_SimpleFilter('bb2Html', ['\View\Format', 'bb2Html']);
$app->view->getInstance()->addFilter($int2time);
$app->view->getInstance()->addFilter($float2euro);
$app->view->getInstance()->addFilter($bb2html);
// register url config
$app->config = ['baseUrl' => $config['baseUrl'],
                'assetsUrl' => "{$config['baseUrl']}/public/assets/{$config['template']}",
                'componentsUrl' => "{$config['baseUrl']}/public/assets/vendor",
                'uploadsDirectory' => $config['uploadsDirectory'],
                'absolutePath' => $config['absolutePath']
];

// register default data that is supplied to the templates
$app->hook('slim.before', function () use ($app, $config) {
    $app->view()->appendData(array_merge($app->config, [
        'session' => ['user' => $app->service_login->getLoggedInUser()],
        'enum' => [
            'activityArea' => \Model\Enum\ActivityArea::toArray(),
            'groupType' => \Model\Enum\GroupType::toArray(),
            'level' => \Model\Enum\Level::toArray(),
            'userRole' => \Model\Enum\UserRole::toArray(),
        ],
    ]));
});

/********************************************************************************
 * Set up I18n
 *******************************************************************************/

// language configuration @see https://github.com/roboter/slim-i18n-working-example/blob/master/htdocs/index.php#L51
$locality = 'nl_NL'; // locality should be determined here
if (defined('LC_MESSAGES')) {
    setlocale(LC_MESSAGES, $locality); // Linux
} else {
    putenv("LC_MESSAGES={$locality}"); // Windows
}

if (false === function_exists('gettext')) {
    throw new \Exception("You do not have the gettext library installed with PHP.");
}

/**
 * Because the .po file is named messages.po, the text domain must be named
 * that as well. The second parameter is the base directory to start
 * searching in.
 */
bindtextdomain('messages', "view/{$config['template']}/locales");
bind_textdomain_codeset('messages', 'UTF-8');
// Tell the application to use this text domain, or messages.mo.
textdomain('messages');

/********************************************************************************
 * Load routes from configuration
 *******************************************************************************/

// load application configuration
$applicationConfig = include("config/config.php");

// load middleware delegate
$middlewareDelegate = function ($routeConfig) {
    return function () use ($routeConfig) {
        if (isset($routeConfig['options']['middleware'])) {
            foreach($routeConfig['options']['middleware'] as $middleware) {
                call_user_func($middleware);
            }
        }
    };
};

// load routes
foreach($applicationConfig['router']['routes'] as $name => $route) {
    switch($route['type']) {
        case 'literal':
            $controller = $route['options']['controller'];
            $action = $route['options']['action'];

            switch($route['method']) {
                case 'get':
                    $app->get(
                        $route['options']['route'],
                        $middlewareDelegate($route),
                        "\\Controller\\{$controller}:{$action}Action"
                    )->name($name);
                    break;
                case 'post':
                    $app->post(
                        $route['options']['route'],
                        $middlewareDelegate($route),
                        "\\Controller\\{$controller}:{$action}Action"
                    )->name($name);
                    break;
                case 'put':
                    $app->put(
                        $route['options']['route'],
                        $middlewareDelegate($route),
                        "\\Controller\\{$controller}:{$action}Action"
                    )->name($name);
                    break;
                case 'delete':
                    $app->delete(
                        $route['options']['route'],
                        $middlewareDelegate($route),
                        "\\Controller\\{$controller}:{$action}Action"
                    )->name($name);
                    break;
            }
            break;
    }
}

/********************************************************************************
 * Load services into the app container
 *******************************************************************************/

// dependency injection
foreach ($applicationConfig['resources'] as $name => $resource) {
    switch($resource['type']) {
        case 'service':
            $class = '\Service\\'.$resource['service'];
            $app->container->singleton($name, function() use ($class) {
              return new $class();
            });
            break;
        case 'mapper':
            $class = '\Mapper\\'.$resource['mapper'];
            $app->container->singleton($name, function() use ($class, $entityManager) {
                return new $class($entityManager);
            });
            break;
    }
}