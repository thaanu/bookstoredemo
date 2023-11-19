<?php 
/**
 * Requests Class
 * @author Ahmed Shan (@thaanu16)
 */

namespace Heliumframework;

use \Heliumframework\Session;

class Requests
{

    static protected $params = [];

    /**
     * Return the REQUEST_URI from $_SERVER array
     */
    public static function uri()
    {

        $uri = $_SERVER['REQUEST_URI'];

        // If has method
        $pos = strpos(trim($uri, '/'), '/');
        if( $pos > 0 ) {

            $__x = explode('/', $uri);
            array_shift($__x);

            // Handling cpanel and api
            if ( in_array($__x[0], [_PREFIX_CPANEL, _PREFIX_API]) ) {
                $uri = '/' . $__x[0] . '/' . $__x[1] . (isset($__x[2]) ? '/' . $__x[2] : '');
                unset($__x[0]);
                unset($__x[1]);
                unset($__x[2]);
            }
            else {
                $uri = '/' . $__x[0] . '/' . $__x[1];
                unset($__x[0]);
                unset($__x[1]);
            }

            if( ! empty($__x) ) {

                self::$params = $__x;

            }

        }

        return parse_url($uri, PHP_URL_PATH);

    }

    /**
     * Return the http request method
     */
    public static function method() 
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ( $method == 'POST' )  {
            if ( isset($_POST['data']) ) {
                parse_str($_POST['data'], $xPost);
                if (isset($xPost['_method'])) {
                    $method = $xPost['_method'];
                }
            }
        }
        Session::put('_method', $method);
        return $method;
    }

    /**
     * Return the post global array
     */
    public static function post()
    {
        return $_POST;
    }

    /**
     * Return the get global array
     */
    public static function get()
    {
        return $_GET;
    }

    public static function params()
    {
        return self::$params;
    }

}