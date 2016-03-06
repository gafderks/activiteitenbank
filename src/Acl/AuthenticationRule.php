<?php

namespace Acl;
use Psr\Http\Message\RequestInterface;

/**
 * Class AuthenticationRule
 *
 * This class decides whether authentication is required.
 * It is basically meant for disabling authentication for public routes.
 *
 * Origin:
 * @see https://github.com/tuupola/slim-jwt-auth/blob/2.x/src/JwtAuthentication/RequestPathRule.php
 *
 * @package Acl
 */
class AuthenticationRule implements \Slim\Middleware\JwtAuthentication\RuleInterface
{
    protected $options = [
        "passthrough" => []
    ];

    /**
     * AuthenticationRule constructor.
     *
     * @param array $options
     */
    public function __construct($options = []) {
        $this->options = array_merge($this->options, $options);
        if (isset($this->options['appConfiguration'])) {
            $this->loadRouteConfiguration();
        }
    }

    /**
     * Checks whether a route is public and thus should not be authenticated.
     *
     * @param RequestInterface $request
     * @return bool whether the route needs to be authenticated
     */
    public function __invoke(RequestInterface $request) {
        // check if combination of method and pattern exists in options[passthrough]

        $uri = "/" . $request->getUri()->getPath();
        $uri = str_replace("//", "/", $uri);

        foreach ($this->options['passthrough'] as $passthroughRoute) {
            // check url
            $pattern = rtrim($passthroughRoute['pattern'], "/");
            $patternMatch = (!!preg_match("@^{$pattern}(/.*)?$@", $uri));

            // check method
            $method = $passthroughRoute['method'];
            $methodMatch = strtolower($request->getMethod()) == strtolower($method);

            // if url and method match a public route, do not authenticate
            if ($methodMatch && $patternMatch) {
                return false;
            }
        }

        return true;
    }

    /**
     * Loads public routes into this object.
     * To determine public routes, the ACL is used in combination with the route configuration.
     */
    private function loadRouteConfiguration() {
        $acl = new \Acl\Acl();
        $applicationConfig = $this->options['appConfiguration'];
        foreach($applicationConfig['router']['routes'] as $name => $route) {
            if ($route['type'] == 'api') { // only api routes need to be authenticated
                if ($acl->isAllowed('guest', $route['acl']['resource'], $route['acl']['privilege'])) {
                    // this route is public, so add it to passthrough
                    array_push($this->options['passthrough'], [
                        'method' => $route['method'],
                        'pattern' => $route['acl']['pattern']
                    ]);
                }
            }
        }
    }
}