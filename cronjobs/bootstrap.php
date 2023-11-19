<?php

    /**
     * CORE HELPER FUNCTIONS
     * ----------------------
     * A set of functions to help the core to function
     */
    include dirname(__DIR__) . '/core/CoreHelpers.php';

    /**
     * PERMALINK
     * ----------------------
     * Setting permalink for respective panels
     */
    include dirname(__DIR__) . '/core/Permalink.php';

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
    include dirname(__DIR__) . '/core/FormHelpers.php';


    /**
     * HELPER FUNCTIONS
     * ----------------------
     * A set of functions to help the application
     */
    include dirname(__DIR__) . '/core/HelperFunctions.php';

    /**
     * TEMPLATE HELPER FUNCTIONS
     * -------------------------
     * A set of functions to help the template to render components
     */
    include dirname(__DIR__) . '/core/TemplateHelpers.php';


    /**
     * CORE FILES
     * ----------
     * Load all core files
     */
    include (dirname(__DIR__) . '/core/Requests.php');
    include (dirname(__DIR__) . '/core/Controller.php');
    include (dirname(__DIR__) . '/core/ApiController.php');
    include (dirname(__DIR__) . '/core/Model.php');
    include (dirname(__DIR__) . '/core/Auth.php');
    include (dirname(__DIR__) . '/core/Notifications.php');


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
    date_default_timezone_set( (string) _env('TIMEZONE') );