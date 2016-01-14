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
            'logout' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/logout',
                    'controller' => 'LoginController',
                    'action' => 'logout',
                ],
            ],
            'activity-explore' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/explore',
                    'controller' => 'ExplorerController',
                    'action' => 'index',
                ],
            ],
            'activity-create' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/create',
                    'controller' => 'EditorController',
                    'action' => 'new',
                ],
            ],
            'activity-edit' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/edit/:id',
                    'controller' => 'EditorController',
                    'action' => 'edit',
                ],
            ],
            'activity-post' => [
                'type' => 'literal',
                'method' => 'post',
                'options' => [
                    'route' => '/edit/:id',
                    'controller' => 'EditorController',
                    'action' => 'post',
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
