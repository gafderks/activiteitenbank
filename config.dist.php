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
    'baseUrl' => 'put public root URL here',
    'absolutePath' => dirname(__FILE__),
    'uploadsDirectory' => dirname(__FILE__) . '/upload',
];
