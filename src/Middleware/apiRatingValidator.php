<?php

namespace Middleware;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class apiRatingValidator
 *
 * Provides methods for validating objects and arrays.
 *
 * @package Middleware
 */
class apiRatingValidator
{

    /**
     * Validates the request body against a definition for a rating.
     * If the validation fails, 400 status is output.
     *
     * @param Request  $request
     * @param Response $response
     * @param          $next
     * @return int|Response
     */
    public function __invoke(Request $request, Response $response, $next) {
        $rating = json_decode($request->getBody());
        $validator =
            v::attribute('rate', v::intType()->min(0)->max(5));
        try {
            $validator->assert($rating);
            $response = $next($request, $response);
            return $response;
        } catch (NestedValidationException $exception) {
            $response = $response->withStatus(400);
            $response->getBody()->write($exception->getFullMessage());
            return $response;
        }
    }

}