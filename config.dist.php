<?php
// /config.dist.php

/**
 * The base configuration file for Activiteitenbank
 *
 * You need to copy this file to "config.php" and fill in the values.
 */

return [

    /**
     * Application name.
     *
     * Default value: 'Activities'
     */
    'applicationName' => 'Activities',

    /**
     * Email of the webmaster.
     * This email is used in the footer of emails e.g. to report abuse.
     */
    'webmasterEmail' => 'put your email here',

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
     * Domain (root URI) that the application will run on.
     * Without trailing slash.
     * WHENEVER POSSIBLE USE HTTPS
     *
     * Example: 'https://example.com'
     */
    'domain' => 'put domain URL here',

    /**
     * Public URL of the /public folder.
     * Without trailing slash.
     * WHENEVER POSSIBLE USE HTTPS
     *
     * Example: 'https://example.com/folder/folder/public'
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
     * Settings for the run environment. Depending on the environment, some features might be disabled.
     */
    'runEnvironment' => [
        /**
         * Whether shell access, e.g. via SSH, is enabled.
         * On shared hosting environments, shell access is often disabled.
         *
         * If shell access is disabled, the following features will be disabled:
         *   - PDF file generation
         *
         * Default value: true
         */
        'shellAccess' => true,
    ],

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
     * Google recaptcha settings.
     *
     * Keys can be registered at @link https://www.google.com/recaptcha/admin
     */
    'recaptcha' => [
        /**
         * This key is used in the HTML code that is served to users.
         */
        'siteKey' => '{siteKey}',

        /**
         * This key is used for communication between the application and Google.
         * Be sure to keep this key a secret.
         */
        'secretKey' => '{secretKey}',
    ],

    /**
     * Email settings.
     */
    'mail' => [
        /**
         * SMTP mail settings
         */
        'smtp' => [
            /**
             * Specify main and backup SMTP servers.
             * Example: 'smtp1.example.com;smtp2.example.com'
             */
            'host' => 'put host here',

            /**
             * Enable SMTP authentication.
             * Default value: true
             */
            'authentication' => true,

            /**
             * SMTP username
             */
            'username' => 'put username here',

            /**
             * SMTP password
             */
            'password' => 'put password here',

            /**
             * Enable TLS encryption, 'ssl' also accepted.
             * Default value: 'tls'
             */
            'secure' => 'tls',

            /**
             * TCP port to connect to.
             * Default value: 587
             */
            'port' => 587,
        ],

        /**
         * Details of the FROM field for emails.
         */
        'from' => [
            /**
             * Email address
             */
            'address' => '',

            /**
             * Natural name
             */
            'name' => '',
        ],

        /**
         * Details of the REPPLYTO field for emails.
         */
        'replyTo' => [
            /**
             * Email address
             */
            'address' => '',

            /**
             * Natural name
             */
            'name' => '',
        ],
    ],

    /**
     * Secret for signing API keys.
     * It is vital that this secret is set to a sufficiently long high-entropy string.
     */
    'apiSecret' => 'put secret here',
];
