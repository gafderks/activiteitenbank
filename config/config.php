<?php

return [
    'router' => [
        'routes' => [
            'index' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/explore',
                    'controller' => 'ExplorerController',
                    'action' => 'index',
                ],
            ],
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
            'activity-view' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/view/:id(/:slug)',
                    'controller' => 'ViewerController',
                    'action' => 'view',
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
                    'route' => '/edit/:id(/:slug)',
                    'controller' => 'EditorController',
                    'action' => 'edit',
                ],
            ],
            'api-activity-post' => [
                'type' => 'literal',
                'method' => 'post',
                'options' => [
                    'route' => '/api/activity',
                    'controller' => 'ActivityController',
                    'action' => 'create',
                    'middleware' => [
                        '\Service\ValidatorService::validateApiActivity',
                    ],
                ],
            ],
            'api-activity-put' => [
                'type' => 'literal',
                'method' => 'put',
                'options' => [
                    'route' => '/api/activity/:id',
                    'controller' => 'ActivityController',
                    'action' => 'update',
                    'middleware' => [
                        '\Service\ValidatorService::validateApiActivity',
                    ],
                ],
            ],
            'api-activity-get' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/api/activity/:id',
                    'controller' => 'ActivityController',
                    'action' => 'get',
                ],
            ],
            'api-activity-delete' => [
                'type' => 'literal',
                'method' => 'delete',
                'options' => [
                    'route' => '/api/activity/:id',
                    'controller' => 'ActivityController',
                    'action' => 'delete',
                ],
            ],
            'api-attachment-post' => [
                'type' => 'literal',
                'method' => 'post',
                'options' => [
                    'route' => '/api/activity/:activityId/attachment',
                    'controller' => 'AttachmentController',
                    'action' => 'upload',
                ],
            ],
            'api-attachment-get' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/api/activity/:activityId/attachment/:attachmentId(/:fileName)',
                    'controller' => 'AttachmentController',
                    'action' => 'download',
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
        'service_activity' => [
            'type' => 'service',
            'service' => 'ActivityService',
        ],
        'service_attachment' => [
            'type' => 'service',
            'service' => 'AttachmentService',
        ],
        'service_validator' => [
            'type' => 'service',
            'service' => 'ValidatorService',
        ],
        'mapper_user' => [
            'type' => 'mapper',
            'mapper' => 'User',
        ],
        'mapper_activity' => [
            'type' => 'mapper',
            'mapper' => 'Activity',
        ],
        'mapper_attachment' => [
            'type' => 'mapper',
            'mapper' => 'Attachment',
        ],
    ],
];
