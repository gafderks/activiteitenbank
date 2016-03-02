<?php
// /config.dist.php

/**
 * The base configuration file for Activiteitenbank
 *
 * You need to copy this file to "config.php" and fill in the values.
 */

return [
    /**
     * Settings for connecting to a database.
     *
     * @see https://doctrine-orm.readthedocs.org/projects/doctrine-dbal/en/latest/reference/configuration.html#getting-a-connection
     */
    'dbConnectionParams' => [

    ],


    /**
     * Name of the view template.
     * The template files need to be located in /view/{template}
     *
     * Default value: 'flatly'
     */
    'template' => 'flatly',

    /**
     * Domain that the application will run on.
     * Without trailing slash.
     */
    'domain' => 'put domain URL here',

    /**
     * Public URL of the /public folder.
     * Without trailing slash.
     */
    'baseUrl' => 'put public root URL here',

    /**
     * Absolute path of the root of the application.
     * Without trailing slash.
     *
     * Default value: dirname(__FILE__)
     */
    'absolutePath' => dirname(__FILE__),

    /**
     * Absolute path of the folder for storing uploads.
     * Without trailing slash.
     *
     * Default value: dirname(__FILE__) . '/upload'
     */
    'uploadsDirectory' => dirname(__FILE__) . '/upload',

    /**
     * Absolute path of the folder for storing temporary files.
     * Without trailing slash.
     *
     * Default value: dirname(__FILE__) . '/temp'
     */
    'tempDirectory' => dirname(__FILE__) . '/temp',

    /**
     * Facebook integration settings.
     */
    'facebook' => [
        /**
         * Facebook login functionality state.
         *
         * Default value: true
         */
        'enableLogin' => true,

        /**
         * App id for the Facebook app that is associated with the application.
         */
        'app_id' => '{app-id}',

        /**
         * App secret for the Facebook app that is associated with the application.
         */
        'app_secret' => '{app-secret}',
    ],

    /**
     * Secret for signing API keys.
     * It is vital that this secret is set to a sufficiently long high-entropy string.
     */
    'apiSecret' => 'put secret here',
];
