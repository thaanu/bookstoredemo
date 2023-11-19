<?php

    /**
     * AREAS
     * -------------------------
     * Areas of the system
     */
    define('_PREFIX_CPANEL', 'cp');
    define('_PREFIX_API', 'api');

    /**
     * CORE HELPER FUNCTIONS
     * ----------------------
     * A set of functions to help the core to function
     */
    include __DIR__ . '/CoreHelpers.php';

    /**
     * PERMALINK
     * ----------------------
     * Setting permalink for respective panels
     */
    include __DIR__ . '/Permalink.php';

    /**
     * COMPOSER MODULES
     * ----------------
     * Load all composer modules
     */
    if( is_dir(dirname(__DIR__) . '/vendor') == false ) {
        // Die and Dump a message
        dd('Please install composer modules <br><br> <code>composer install</code>');
    }
    require(dirname(__DIR__) . '/vendor/autoload.php');


    /**
     * LIBRARY
     * ---------------
     * Load all the libraries
     */
    $libraries = scandir(dirname(__DIR__) . '/library');
    foreach( $libraries as $library ) {
        $ext = pathinfo($library)['extension'];
        if( $ext == 'php' ) {
            include_once(dirname(__DIR__) . '/library/' . $library);
        }
    }
    

    /**
     * FORM HELPER FUNCTIONS
     * ----------------------
     * A set of functions to help the core to function a form
     */
    include __DIR__ . '/FormHelpers.php';


    /**
     * HELPER FUNCTIONS
     * ----------------------
     * A set of functions to help the application
     */
    include __DIR__ . '/HelperFunctions.php';

    /**
     * TEMPLATE HELPER FUNCTIONS
     * -------------------------
     * A set of functions to help the template to render components
     */
    include __DIR__ . '/TemplateHelpers.php';


    /**
     * CORE FILES
     * ----------
     * Load all core files
     */
    require(__DIR__ . '/Requests.php');
    require(__DIR__ . '/Controller.php');
    require(__DIR__ . '/ApiController.php');
    require(__DIR__ . '/Model.php');
    require(__DIR__ . '/Auth.php');
    require(__DIR__ . '/Notifications.php');


    /**
     * Error Reporting
     * ---------------
     * Error showing or logging
     */
    ini_set('display_errors', 0);
    if( _env('DEV_MODE') == 'true' ){ 
        ini_set('display_errors', 1);
    }


    /**
     * TIMEZONE
     * --------------
     * Set the timezone
     */
    date_default_timezone_set( _env('TIMEZONE') );
    
    /**
     * SESSION
     * ---------------
     * Everything about session and security
     */
    // Prevents javascript XSS attacks aimed to steal the session ID
    if ( _env('DEV_MODE') == 'false' ) {
        ini_set('session.cookie_httponly', 1);
    }

    // Session ID cannot be passed through URLs
    if ( _env('DEV_MODE') == 'false' ) {
        ini_set('session.use_only_cookies', 1);
    }

    // Uses a secure connection (HTTPS) if possible
    if ( _env('DEV_MODE') == 'false' ) {
        ini_set('session.cookie_secure', 1);
    }

    //  Initialize Session
    session_start();


    /**
     * ROUTER
     * --------------
     * Router Module
     */

    include __DIR__ . '/Router.php';
    \Heliumframework\Router::load(dirname(__DIR__) . '/Routing.php')
        ->direct(\Heliumframework\Requests::uri(), \Heliumframework\Requests::method());
