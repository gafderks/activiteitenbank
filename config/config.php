<?php

return [
    'router' => [
        'routes' => [
            'login-form' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/login',
                    'controller' => 'LoginController',
                    'action' => 'index',
                ],
            ],
            'login' => [
                'type' => 'literal',
                'method' => 'post',
                'options' => [
                    'route' => '/login',
                    'controller' => 'LoginController',
                    'action' => 'post',
                ],
            ],
            'explorer' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/explore',
                    'controller' => 'ExplorerController',
                    'action' => 'index',
                ],
            ],
        ],
    ],
    'resources' => [
        'service_login' => [
            'type' => 'service',
            'service' => 'LoginService',
        ],
        'service_user' => [
            'type' => 'service',
            'service' => 'UserService',
        ],
        'mapper_user' => [
            'type' => 'mapper',
            'mapper' => 'User',
        ]
    ],
];
