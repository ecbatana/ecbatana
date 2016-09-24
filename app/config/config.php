<?php
/**
 * This file is used for dependency injection into some required class,
 * there are some configurations such as database configuration,
 * environment configuration, timezone configuration, etc.
 *
 * @return array
 */

return array(
    
    /**
     * Application Environment
     *
     * This configuration is used to set the environment for your application,
     * the choice is two, development or production.
     */
    'environment' => 'development',

    /**
     * Timezone Configuration
     * 
     * This optional which to set the timezone, you can change this depends
     * your local timezone, the default value is 'UTC+7'.
     */
    'timezone' => 'UTC+7',

    /**
     * Database Configuration
     * 
     * This configuration is used to set the database configuration, such as
     * database driver, host, etc. this database using PDO Class.
     *
     * But for now, we only accept mysql as database driver, stay tune for
     * upcoming update!
     */
    'database' => array(
        'fetchmode' => PDO::FETCH_ASSOC,
        'driver'    => 'mysql',
        'host'      => '127.0.0.1',
        'database'  => 'ecbatana',
        'username'  => 'root',
        'password'  => '',
        'charset'   => null
    ),

    /**
     * Url Configuration
     * 
     * This configuration is used to set the path of this application, and 
     * set path for some class, such as viewpath, etc.
     */
    
    'url' => array(
        'dirprefix' => '',
        'app' => '../app',
        'controller' => '../app/controller',
        'model' => '../app/model',
        'view' => '../app/view',
        'routes' => '../app/routes.php'
    ),
);
