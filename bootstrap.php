<?php
// /bootstrap.php

use Doctrine\ORM\Tools\Setup;

// load required files
require_once "vendor/autoload.php";
$config = include("config.php");

// create autoloader
function __autoload($className) {
    include __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . str_replace('\\', '/', $className) . '.php';
}
spl_autoload_register('__autoload');

\Slim\Slim::registerAutoloader();

// absolute path to the root directory
if ( !defined('ABSPATH') )
    define('ABSPATH', $config['absolutePath']);

// create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$doctrineConfig = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/src"], $isDevMode);

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
//    'locales.path'   => "public/assets/{$config['template']}/locales"
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
// register template assets url in view
$app->hook('slim.before', function () use ($app, $config) {
    $app->view()->appendData([
        'baseUrl' => $config['baseUrl'],
        'assetsUrl' => "{$config['baseUrl']}/public/assets/{$config['template']}",
        'session' => ['user' => $app->service_login->getLoggedInUser()],
        'enum' => [
            'activityArea' => \Model\Enum\ActivityArea::toArray(),
            'groupType' => \Model\Enum\GroupType::toArray(),
            'level' => \Model\Enum\Level::toArray(),
            'userRole' => \Model\Enum\UserRole::toArray(),
        ],
    ]);
});

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
                    )->name($name);
                    break;
                case 'post':
                    $app->post(
                        $route['options']['route'],
                        "\\Controller\\{$controller}:{$action}Action"
                    )->name($name);
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