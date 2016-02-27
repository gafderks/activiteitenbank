<?php
// /config-sample.php

/**
 * The base configuration file for Activiteitenbank
 *
 * You need to copy this file to "config.php" and fill in the values.
 *
 * This file contains the following configurations:
 * - Database settings
 * - Template
 * - baseUrl
 * - absolutePath
 */

return [
    'dbConnectionParams' => [
        // fill in connection parameters
        // (see https://doctrine-orm.readthedocs.org/projects/doctrine-dbal/en/latest/reference/configuration.html#getting-a-connection
        // for details)
    ],
    'template' => 'flatly',
    'domain' => 'put domain URL here',
    'baseUrl' => 'put public root URL here',
    'absolutePath' => dirname(__FILE__),
    'uploadsDirectory' => dirname(__FILE__) . '/upload',
    'tempDirectory' => dirname(__FILE__) . '/temp',
    'facebook' => [
        'enableLogin' => true,
        'app_id' => '{app-id}',
        'app_secret' => '{app-secret}',
        'default_graph_version' => 'v2.5',
    ],
];
