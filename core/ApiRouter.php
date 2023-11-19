<?php
/**
 * API Router Class
 * @author Ahmed Shan (@thaanu16)
 */
namespace Heliumframework;
class ApiRouter
{

    static protected $routes = [];
    static protected $parameters = [];

    /**
     * Handle GET methods
     * @param string $route
     * @param string $controllerMethod
     */
    public static function get( $route = '/', $controllerMethod )
    {

        $explode            = explode('@', $controllerMethod);
        self::$routes[ltrim($route,'/')] = ['controller' => $explode[0], 'method' => $explode[1] ];

    }

    /**
     * Load the URL
     */
    public static function loadURL()
    {

        $route          = $_SERVER['REQUEST_URI'];
        $urlParse       = explode('/', $route);
        array_shift($urlParse);
        
        @$controller     = $urlParse[1];

        // Check if the route exists
        if( array_key_exists($controller, self::$routes) ) {
            
            $controllerFile = dirname(__DIR__) . '/controllers/api/' . self::$routes[$controller]['controller'] . '.php';

            // Check whether controller file exists
            if( file_exists($controllerFile) ) {

                // Set method
                // Check for numeric value
                if( isset($urlParse[3]) && is_numeric($urlParse[3]) ) {

                    // Set numeric special route
                    if( isset(self::$routes[$urlParse[1].'/'.$urlParse[2].'/{}']) ) {
                        $method = self::$routes[$urlParse[1].'/'.$urlParse[2].'/{}']['method'];
                    }
                    // Set a numeric special route followed by another parameter
                    elseif( isset(self::$routes[$urlParse[1].'/'.$urlParse[2].'/{}/'.$urlParse[4]]) ) {
                        $method = self::$routes[$urlParse[1].'/'.$urlParse[2].'/{}/'.$urlParse[4]]['method'];   
                    }
                    // Set variables
                    elseif( isset(self::$routes[$urlParse[1].'/'.$urlParse[2].'/{}/{}']) ) {
                        $method = self::$routes[$urlParse[1].'/'.$urlParse[2].'/{}/{}']['method'];
                    }
                    elseif( isset(self::$routes[$urlParse[1].'/'.$urlParse[2].'/{}/{}/'.$urlParse[5]]) ) {
                        $method = self::$routes[$urlParse[1].'/'.$urlParse[2].'/{}/{}/'.$urlParse[5]]['method'];
                    }
                    
                } else {
                    
                    // Show normal route
                    if( isset(self::$routes[ltrim($route, '/api')]['method']) ) {
                        $method = self::$routes[ltrim($route, '/api')]['method'];
                    }

                }

                // Load the controller
                include( $controllerFile );

                $controllerToLoad = new self::$routes[$controller]['controller']();

                // Remove Controller and Method
                unset($urlParse[0]); unset($urlParse[1]); unset($urlParse[2]);

                // Set parameters
                if( !empty($urlParse) ) {
                    self::$parameters = array_values($urlParse);
                }

                // Check whether the metho is allowed                    
                if( isset($method) && method_exists( $controllerToLoad, $method) ) {
                        
                    $params = ( !empty(self::$parameters) ? self::$parameters : [] );
                    call_user_func_array( [$controllerToLoad, $method], $params );
                    
                }
                // Else, send a 404 error
                else {

                    // Log the error
                    error_log("Unable to find method");

                    // Show 404 Page Not Found
                    error_header(404);

                }

            }
            // Else, sende a 404 error
            else {

                // Show 404 Page Not Found
                error_header(404);

            }
            
        }
        else {

            // Log the error
            error_log('Controller file not found');

            // Show 404 Page Not Found
            error_header(404);

        }


    }


    public static function test()
    {
        echo '<pre>'; print_r(self::$routes); echo '</pre>';
    }

}

