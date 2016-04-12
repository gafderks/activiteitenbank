<?php

namespace Middleware;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class apiCommentValidator
 *
 * Provides methods for validating objects and arrays.
 *
 * @package Middleware
 */
class apiCommentValidator
{

    /**
     * Validates the request body against a definition for a comment.
     * If the validation fails, 400 status is output.
     *
     * @param Request  $request
     * @param Response $response
     * @param          $next
     * @return int|Response
     */
    public function __invoke(Request $request, Response $response, $next) {
        $comment = json_decode($request->getBody());
        $validator =
            v::attribute('comment', v::stringType())
                ->attribute('didIt', v::boolType());
        try {
            $validator->assert($comment);
            $response = $next($request, $response);
            return $response;
        } catch (NestedValidationException $exception) {
            $response = $response->withStatus(400);
            $response->getBody()->write($exception->getFullMessage());
            return $response;
        }
    }

}