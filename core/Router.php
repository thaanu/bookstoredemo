<?php
/**
 * Router Class
 * @author Ahmed Shan (@thaanu16)
 */
namespace Heliumframework;

use \Heliumframework\Requests;
use Exception;

class Router
{

    static protected $routes = [
        'GET' => [],
        'PUT' => [],
        'DELETE' => [],
        'POST' => []
    ];

    public static function load( $file )
    {
        $router = new static;
        require $file;
        return $router;
    }

    /**
     * Serve a GET request
     */
    public static function get( $uri, $controller )
    {
        $split = explode('@', $controller);
        self::$routes['GET'][$uri] = ['controller' => $split[0], 'method' => $split[1]];
    }


    /**
     * Serve a POST request
     */
    public static function post( $uri, $controller )
    {
        $split = explode('@', $controller);
        self::$routes['POST'][$uri] = ['controller' => $split[0], 'method' => $split[1]];
    }

    /**
     * Serve a PUT request
     */
    public static function put( $uri, $controller )
    {
        $split = explode('@', $controller);
        self::$routes['PUT'][$uri] = ['controller' => $split[0], 'method' => $split[1]];
    }

    /**
     * Serve a DELETE request
     */
    public static function delete( $uri, $controller )
    {
        $split = explode('@', $controller);
        self::$routes['DELETE'][$uri] = ['controller' => $split[0], 'method' => $split[1]];
    }

    /**
     * Redirect traffic to requested controller
     * @param string $uri
     * @param string $requestType
     */
    public function direct( $uri, $requestType )
    {
        
        if( array_key_exists(trim($uri), self::$routes[$requestType]) ) {

            $__x = self::$routes[$requestType][$uri];

            $apiPrefixLen = strlen(_PREFIX_API) + 1;
            $cpanelPrefixLen = strlen(_PREFIX_CPANEL) + 1;

            $controllerDir = '/controllers/web/';

            // Switch path for API
            if (substr($uri, 0, $apiPrefixLen) === '/'._PREFIX_API) {
                $controllerDir = '/controllers/'._PREFIX_API.'/';
            } else {
                if (substr($uri, 0, $cpanelPrefixLen) === '/'._PREFIX_CPANEL) {
                    $controllerDir = '/controllers/'._PREFIX_CPANEL.'/';
                }
            }


            // $controllerDir = ( substr($uri, 0, $apiPrefixLen) === '/'._PREFIX_API ? '/controllers/'._PREFIX_API.'/' : '/controllers/web/') ;
            // $controllerDir = ( substr($uri, 0, $cpanelPrefixLen) === '/'._PREFIX_CPANEL ? '/controllers/'._PREFIX_CPANEL.'/' : '/controllers/web/') ;

            $controller_file = dirname(__DIR__) . $controllerDir . $__x['controller'] . '.php';

            // Check if controller exists
            if( file_exists( $controller_file ) ) {

                require $controller_file;

                // Create new instance of the controller
                $controller = new $__x['controller']();

                // Check if method exists
                if( method_exists($controller, $__x['method']) ) {

                    // Call method and parse parameters
                    call_user_func_array( [$controller, $__x['method']], Requests::params() );

                }
                // Else, throw exception
                else {
                    // throw new Exception('Method not found');
                    log_message("Method not found");
                    error_header(404);
                }

            }
            else {
                echo "Controller not found";
                // log_message("Controller not found");
                // error_header(404);
            }

        }
        else {

            log_message("No route set for $uri");
            error_header(404);

        }

    }

}

