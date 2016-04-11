<?php
// /src/Controller/TokenController.php

namespace Controller;

use Acl\Scope;
use Knp\Snappy\Pdf;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Exceptions\NullTypeException;

/**
 * Class TokenController
 *
 * @package Controller
 */
class TokenController extends Controller
{

    /**
     * Looks up the details of an activity and returns them if found.
     * Outputs a 404 status if the activity with the specified id was not found.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args route parameters
     * @return \Psr\Http\Message\MessageInterface|Response
     */
    public function generateAction(Request $request, Response $response, $args = []) {
        try {
            // if token is not allowed to generate a token, output 401
            $user = $this->getJwtService()->getUser($this->container['jwt']);
            if (!$this->container['acl']->isAllowed($user->getRole()->value(), 'token')) {
                return $this->getExceptionResponse($response,
                    new \Exception("You are not allowed to perform this action"), 401);
            }

            // load input
            $input = json_decode($request->getBody());
            $scope = get_object_vars($input->scopes);
            // remove permissions that are not allowed
            foreach ($scope as $resource => $privileges) {
                if ($privileges === null) {
                    if (!$this->container['acl']->isAllowed($user->getRole()->value(), $resource)) {
                        // remove privilege
                        unset($scope[$resource]);
                    }
                } else {
                    foreach ($privileges as $key => $privilege) {
                        if (!$this->container['acl']->isAllowed($user->getRole()->value(), $resource, $privilege)) {
                            // remove privilege
                            unset($scope[$resource][$key]);
                            if (count($scope[$resource]) === 0) {
                                unset($scope[$resource]);
                            }
                        }
                    }
                    $scope[$resource] = array_values($scope[$resource]);
                }
            }

            $token = $this->getJwtService()->generateToken($user,
                new Scope($scope),
                [
                    'iat' => $input->iat,
                    'exp' => $input->exp,
                ]
            );

            return $this->getPlainResponse($response, $token, 200);
        } catch(\Exception $exception) {
            return $this->getExceptionResponse($response, $exception, 404);
        }
    }

    /**
     * Get the JWT service.
     *
     * @return \Service\JwtService
     */
    protected function getJwtService() {
        return $this->container['service_jwt'];
    }

}