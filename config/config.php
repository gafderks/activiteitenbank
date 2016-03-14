<?php

return [
    'router' => [
        'routes' => [
            'index' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/',
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
                'method' => 'post',
                'options' => [
                    'route' => '/logout',
                    'controller' => 'LoginController',
                    'action' => 'logout',
                ],
            ],
            'settings' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/settings',
                    'controller' => 'SettingsController',
                    'action' => 'index',
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
                    'route' => '/view/{id}[/{slug}]',
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
                    'route' => '/edit/{id}[/{slug}]',
                    'controller' => 'EditorController',
                    'action' => 'edit',
                ],
            ],
            'api-activity-post' => [
                'type' => 'api',
                'method' => 'post',
                'options' => [
                    'route' => '/api/activity',
                    'controller' => 'ActivityController',
                    'action' => 'create',
                    'middleware' => [
                        '\Middleware\apiActivityValidator',
                    ],
                ],
                'acl' => [
                    'resource' => 'activity',
                    'privilege' => 'create',
                    'pattern' => '/api/activity/\d+',
                ],
            ],
            'api-activity-put' => [
                'type' => 'api',
                'method' => 'put',
                'options' => [
                    'route' => '/api/activity/{id}',
                    'controller' => 'ActivityController',
                    'action' => 'update',
                    'middleware' => [
                        '\Middleware\apiActivityValidator',
                    ],
                ],
                'acl' => [
                    'resource' => 'activity',
                    'privilege' => 'edit',
                    'pattern' => '/api/activity/\d+',
                ],
            ],
            'api-activity-get' => [
                'type' => 'api',
                'method' => 'get',
                'options' => [
                    'route' => '/api/activity/{id}',
                    'controller' => 'ActivityController',
                    'action' => 'get',
                ],
                'acl' => [
                    'resource' => 'activity',
                    'privilege' => 'view',
                    'pattern' => '/api/activity/\d+',
                ],
            ],
            'api-activity-get-pdf' => [
                'type' => 'api',
                'method' => 'get',
                'options' => [
                    'route' => '/api/activity/{id}/pdf',
                    'controller' => 'ActivityController',
                    'action' => 'generatePdf',
                ],
                'acl' => [
                    'resource' => 'activity',
                    'privilege' => 'view',
                    'pattern' => '/api/activity/\d+/pdf',
                ],
            ],
            'api-activity-delete' => [
                'type' => 'api',
                'method' => 'delete',
                'options' => [
                    'route' => '/api/activity/{id}',
                    'controller' => 'ActivityController',
                    'action' => 'delete',
                ],
                'acl' => [
                    'resource' => 'activity',
                    'privilege' => 'delete',
                    'pattern' => '/api/activity/\d+',
                ],
            ],
            'api-attachment-post' => [
                'type' => 'api',
                'method' => 'post',
                'options' => [
                    'route' => '/api/activity/{activityId}/attachment',
                    'controller' => 'AttachmentController',
                    'action' => 'upload',
                ],
                'acl' => [
                    'resource' => 'activity',
                    'privilege' => 'edit',
                    'pattern' => '/api/activity/\d+/attachment',
                ],
            ],
            'api-attachment-get' => [
                'type' => 'api',
                'method' => 'get',
                'options' => [
                    'route' => '/api/activity/{activityId}/attachment/{attachmentId}[/{fileName}]',
                    'controller' => 'AttachmentController',
                    'action' => 'download',
                ],
                'acl' => [
                    'resource' => 'activity',
                    'privilege' => 'view',
                    'pattern' => '/api/activity/\d+/attachment/\d+/?.*',
                ],
            ],
            'api-attachment-delete' => [
                'type' => 'api',
                'method' => 'delete',
                'options' => [
                    'route' => '/api/activity/{activityId}/attachment/{attachmentId}[/{fileName}]',
                    'controller' => 'AttachmentController',
                    'action' => 'delete',
                ],
                'acl' => [
                    'resource' => 'activity',
                    'privilege' => 'edit',
                    'pattern' => '/api/activity/\d+/attachment/\d+/?.*',
                ],
            ],
            'facebook-login-callback' => [
                'type' => 'literal',
                'method' => 'get',
                'options' => [
                    'route' => '/login/facebook/callback',
                    'controller' => 'LoginController',
                    'action' => 'facebookCallback',
                ],
            ],
        ],
    ],
    'resources' => [
        'service_login' => [
            'type' => 'service',
            'service' => 'LoginService',
        ],
        'service_facebook' => [
            'type' => 'service',
            'service' => 'FacebookService',
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
        'service_pdf' => [
            'type' => 'service',
            'service' => 'PdfService',
        ],
        'service_jwt' => [
            'type' => 'service',
            'service' => 'JwtService',
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
