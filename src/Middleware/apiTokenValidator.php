<?php

namespace Middleware;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class apiTokenValidator
 *
 * Provides methods for validating requests for tokens.
 *
 * @package Middleware
 */
class apiTokenValidator
{

    /**
     * Validates the request body against a definition for an activity.
     * If the validation fails, 400 status is output.
     *
     * @param Request  $request
     * @param Response $response
     * @param          $next
     * @return int|Response
     */
    public function __invoke(Request $request, Response $response, $next) {
        $acl = new \Acl\Acl();

        $tokenRequest = json_decode($request->getBody());
        //var_dump($tokenRequest->scopes); die;
        $validator =
            v::attribute('iat', v::intType()->min(0))
                ->attribute('exp', v::intType()->min(0))
                ->attribute('scopes', v::objectType()
                );
        try {
            $validator->assert($tokenRequest);
            $response = $next($request, $response);
            return $response;
        } catch (NestedValidationException $exception) {
            $response = $response->withStatus(400);
            $response->getBody()->write($exception->getFullMessage());
            return $response;
        }
    }

}