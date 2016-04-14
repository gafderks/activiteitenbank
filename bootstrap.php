<?php
// /bootstrap.php

use Doctrine\ORM\Tools\Setup;

/********************************************************************************
 * Load configuration
 *******************************************************************************/

$config = include("config.php");
$applicationConfig = include("config/config.php");

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
$app = new \Slim\App([
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
    ],
]);

$container = $app->getContainer();

// register url config
$container['config'] = function ($container) use ($config) {
    return array_merge($config, [
        'assetsUrl' => "{$config['baseUrl']}/assets/{$config['template']}",
        'componentsUrl' => "{$config['baseUrl']}/assets/vendor",
    ]);
};

// Twig configuration
$container['view'] = function($container) use ($config) {
    $view = new \Slim\Views\Twig("view/{$config['template']}/templates", [
        'cache' => "view/{$config['template']}/cache",
        'debug' => true,
        'auto_reload' => true,
    ]);

    // twig extensions
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));
    $view->addExtension(new \Twig_Extension_Debug());
    $view->addExtension(new \Twig_Extensions_Extension_I18n());
    $view->addExtension(new \Twig_Extensions_Extension_Intl());
    $view->addExtension(new \View\Extension\FormatTwigExtension($container));
    $view->addExtension(new \Twig_Extensions_Extension_Date());

    // register default data that is supplied to the templates
    $predefinedData = array_merge($container['config'], [
        'session' => ['user' => $container['service_login']->getLoggedInUser()],
        'enum' => [
            'activityArea' => \Model\Enum\ActivityArea::toArray(),
            'groupType' => \Model\Enum\GroupType::toArray(),
            'level' => \Model\Enum\Level::toArray(),
            'userRole' => \Model\Enum\UserRole::toArray(),
        ],
    ]);
    foreach($predefinedData as $key => $value) {
        $view->offsetSet($key, $value);
    }

    return $view;
};

/********************************************************************************
 * Initialize ACL
 *******************************************************************************/

// register ACL service as a singleton
$container['acl'] = function() {
    return new \Acl\Acl();
};

$app->add(new \Slim\Middleware\JwtAuthentication([
    'path' => '/api',
    'secret' => $config['apiSecret'],
    'callback' => function($request, $response, $arguments) use ($container) {
        // store jwt for later use
        $container['jwt'] = $arguments['decoded'];
        // check if user is also allowed these privileges with the current role
        $jwtService = new \Service\JwtService($container);
        return $jwtService->authorizeToken($arguments['decoded']);
    },
    'rules' => [ // disable authentication on public routes
        new \Acl\AuthenticationRule([
            'appConfiguration' => $applicationConfig
        ]),
    ],
]));

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

// load routes
foreach($applicationConfig['router']['routes'] as $name => $route) {
    switch($route['type']) {
        case 'literal' || 'api':
            $controller = $route['options']['controller'];
            $action = $route['options']['action'];

            switch($route['method']) {
                case 'get':
                    $subject = $app->get(
                        $route['options']['route'],
                        "\\Controller\\{$controller}:{$action}Action"
                    )->setName($name);
                    break;
                case 'post':
                    $subject = $app->post(
                        $route['options']['route'],
                        "\\Controller\\{$controller}:{$action}Action"
                    )->setName($name);
                    break;
                case 'put':
                    $subject = $app->put(
                        $route['options']['route'],
                        "\\Controller\\{$controller}:{$action}Action"
                    )->setName($name);
                    break;
                case 'delete':
                    $subject = $app->delete(
                        $route['options']['route'],
                        "\\Controller\\{$controller}:{$action}Action"
                    )->setName($name);
                    break;
            }

            // register middleware
            if (isset($route['options']['middleware'])) {
                foreach($route['options']['middleware'] as $middleware) {
                    $subject->add(new $middleware());
                }
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
            $container[$name] = function($container) use ($class) {
              return new $class($container);
            };
            break;
        case 'mapper':
            $class = '\Mapper\\'.$resource['mapper'];
            $container[$name] = function($container) use ($class, $entityManager) {
                return new $class($container, $entityManager);
            };
            break;
    }
}