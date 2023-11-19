<?php
    /**
     * Some functions to help the core
     * @author Ahmed Shan (@thaanu16)
     */

    use Heliumframework\Session;
    use function Sodium\memcmp;

/**
     * A function to get the environment file
     * @param string $var
     * @return string
     */
    function _env( $var )
    {
        $config = [];
        $env_file = dirname(__DIR__) . '/.env';

        try {

            // Check if environment type 
            if( file_exists($env_file) == false ) { throw new Exception('Environment file not found.'); }

            $lines = file($env_file);
            if( empty($lines) ){ throw new Exception('Environment file is empty. O.o'); }

            // Loop all the environment file variables
            foreach( $lines as $line ) {
                // Validate Line by checking if there is an equal sign
                if( strpos($line, '=') > 0 ) {
                    list($f, $l) = explode('=', $line);
                    $value = trim($line, $f);
                    $value = ltrim($value, '=');
                    $config[$f] = trim($value);
                }
            }

            // Return the value, or return false
            return ( array_key_exists($var, $config) ? $config[$var] : false );

        } catch( Exception $e ) {

            die( $e->getMessage() );

        }
        
    }

    /**
     * A function to get the environment file and output the variable
     * @param string $var
     * @return void
     */
    function env( $var ) 
    {
        echo _env($var);
    }

    /**
     * Dump & Die - Show a message and die
     * @param void $message
     * @return string
     */
    function dd( $message = '' )
    {
        $msg = '';

        if( !empty($message) ) {

            $msg .= '<div style="padding: 40px; border: 5px solid red; background: #eee; font-family: helvetica;">';
            $msg .= '<h2 style="padding:0; margin:0 0 20px 0; text-transform: uppercase;">Die &amp; Dump</h2>';

            // Handle Array
            if( is_array($message) ) {
                $msg .= '<pre>';
                $msg .= print_r($message, true);
                $msg .= '</pre>';
            }
            else {
                $msg .= $message;
            }


            $msg .= '</div>';

        }

        die($msg);

    }

    /**
     * Display a message, only used for debugging (Works on in DEV MODE)
     * @param string $message
     */
    function debugMsg( $message ) 
    {
        // Check whether the app is in dev mode
        if( _env('DEV_MODE') ) {
            echo '<div style="padding: 10px; border: 5px solid #333; margin: 20px 0; font-family: helvetica;">';
            echo '<h6 style="color: purple; margin: 0; padding: 0;">DEBUG MESSAGE</h6>';
            echo '<pre>'; print_r($message); echo '</pre>';
            echo '</div>';
        }
    }

    /**
     * Error headers
     * @param int $errorcode
     * @return void
     */
    function error_header( Int $errorcode )
    {
        switch( $errorcode ) {
            case 400:
                header("HTTP/1.0 400 Bad Request");
                include(dirname(__DIR__) . '/errors/400.html'); exit; break;
            case 404:
                header("HTTP/1.0 404 Not Found");
                include(dirname(__DIR__) . '/errors/404.html'); exit; break;
            case 500:
                header("HTTP/1.0 500 Internal Server Error");
                include(dirname(__DIR__) . '/errors/500.html'); exit; break;
            case 401:
                header("HTTP/1.0 401 Unauthorized");
                include(dirname(__DIR__) . '/errors/401.html'); exit; break;
            case 419:
                include(dirname(__DIR__) . '/errors/419.html'); exit; break;

        }
    }

    /**
     * Load CSS and JS files
     * @param string path
     * @return string
     */
    function assets( $path )
    {
        echo _env('ABS_PATH') . $path;
    }

    /**
     * Redirect user
     * @param string $path
     * @return void
     */
    function redirectTo( $path )
    {
        Header('location: ' . $path);
        exit;
    }

    /**
     * Find the active menu
     *
     * @return boolean
     */
    function isActiveMenu( $selectedkey )
    {
        $urlParser = explode('/', ltrim($_SERVER['REQUEST_URI'], '/'));
        $navigation = json_decode(file_get_contents(dirname(__DIR__) . '/resources/navigation.json'), true);
        $menu = $navigation[$selectedkey];
        foreach( $menu['submenu'] as $submenu ) {
            $l = ltrim($submenu['url'], "/");
            if($l == $urlParser[0]) {
                return true;
            }
        }
        return false;
    }

    /**
     * Find the active tab
     * @param string $route
     * @param int $placement
     * @return boolean
     */
    function isActiveTab( $route, $placement = null )
    {

        $urlParser = explode('/', ltrim($_SERVER['REQUEST_URI'], '/'));
        $urlParserCount = count($urlParser) - 1;

        $routeParser = explode('/', ltrim($route, '/'));
        $routeParserCount = count($routeParser) - 1;

        // Check if placement is let
        if( is_numeric($placement) ) {

            // Check whether the tabs match
            if( $urlParser[$placement] == $routeParser[$placement] ) {
                return true;
            }

        }
        // Else, if no placement is set
        else {
            
            // Check whether array count matches
            if($urlParserCount == $routeParserCount) {
    
                // Check whether the tabs match
                if( $urlParser[$urlParserCount] == $routeParser[$routeParserCount] ) {
                    return true;
                }
    
            }

        }

        return false;
        
    }

    /**
     * Get the labels from Request URI in an array
     *
     * @return  array  Returns the cleaned up version of request uri labels
     */
    function breadCrumb()
    {
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        unset($uri[0]);
        array_shift($uri);
        if ( is_numeric($uri[count($uri)-1]) ) { unset($uri[count($uri)-1]); }
        return $uri;
    }

    /**
     * Log a given message
     * @param string $message
     * @param string $file_path | optional
     */
    function log_message( $message, $file_path = '' )
    {
        $logFile = dirname(__DIR__) . '/logs/logFile.txt';

        if( $file_path != '' ) {
            $logFile = $file_path;
        }

        // Create a file if does not exist
        if( file_exists($logFile) == false ) {
            touch($logFile);
        }

        // Read File
        $oldContent = file_get_contents($logFile);

        // Append
        $newContent = date('Y-m-d H:i:s') . "\t";
        $newContent .= $message . "\n";
        $newContent .= $oldContent;

        // Write File
        file_put_contents($logFile, $newContent);

    }

    /**
     * Get system information, this function only works on LINUX operating system
     * Available Properties: cpu, cpu_model, mem_percent, mem_total, mem_free, mem_used, hdd_free, hdd_total, hdd_used, hdd_percent, network_rx, network_tx
     *
     * @param   string  $property  Property to return (optional)
     *
     * @return  mixed             Returns the speicifc property or an array of system information
     */
    function getSystemInfo( $property = '' )
    {

        try {

            if ( strtoupper(substr(PHP_OS, 0, 3)) != 'LIN' ) {
                throw new Exception('This function works on Linux Operating Systems');
            }

            //cpu stat
            $prevVal = shell_exec("cat /proc/stat");
            $prevArr = explode(' ',trim($prevVal));
            $prevTotal = $prevArr[2] + $prevArr[3] + $prevArr[4] + $prevArr[5];
            $prevIdle = $prevArr[5];
            usleep(0.15 * 1000000);
            $val = shell_exec("cat /proc/stat");
            $arr = explode(' ', trim($val));
            $total = $arr[2] + $arr[3] + $arr[4] + $arr[5];
            $idle = $arr[5];
            $intervalTotal = intval($total - $prevTotal);
            $stat['cpu'] =  intval(100 * (($intervalTotal - ($idle - $prevIdle)) / $intervalTotal));
            $cpu_result = shell_exec("cat /proc/cpuinfo | grep model\ name");
            $stat['cpu_model'] = strstr($cpu_result, "\n", true);
            $stat['cpu_model'] = str_replace("model name    : ", "", $stat['cpu_model']);
            //memory stat
            $stat['mem_percent'] = round(shell_exec("free | grep Mem | awk '{print $3/$2 * 100.0}'"), 2);
            $mem_result = shell_exec("cat /proc/meminfo | grep MemTotal");
            $stat['mem_total'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
            $mem_result = shell_exec("cat /proc/meminfo | grep MemFree");
            $stat['mem_free'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
            $stat['mem_used'] = $stat['mem_total'] - $stat['mem_free'];
            //hdd stat
            $stat['hdd_free'] = round(disk_free_space("/") / 1024 / 1024 / 1024, 2);
            $stat['hdd_total'] = round(disk_total_space("/") / 1024 / 1024/ 1024, 2);
            $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
            $stat['hdd_percent'] = round(sprintf('%.2f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 2);
            //network stat
            // $stat['network_rx'] = round(trim(file_get_contents("/sys/class/net/eth0/statistics/rx_bytes")) / 1024/ 1024/ 1024, 2);
            // $stat['network_tx'] = round(trim(file_get_contents("/sys/class/net/eth0/statistics/tx_bytes")) / 1024/ 1024/ 1024, 2);
       
            if ( ! empty($property) ) {
                $availableProperties = ['cpu', 'cpu_model', 'mem_percent', 'mem_total', 'mem_free', 'mem_used', 'hdd_free', 'hdd_total', 'hdd_used', 'hdd_percent', 'network_rx', 'network_tx'];
                if ( ! in_array($property, $availableProperties) ) {
                    throw new Exception("$property is not available");
                }
                return $stat[$property];
            }
            return $stat;
        }
        catch ( Exception $ex ) {
            return $ex->getMessage();
        }
    }

    // function getPermalink( $link, $prefix = ''  )
    // {
    //     echo permalink($link, $prefix);
    // }
    
    // function permalink( $link, $prefix = '' )
    // {
    //     return '/' . ( empty($prefix) ? '' : $prefix ) . str_replace('.', '/', $link);
    // }

    function cpanelPermalink($url)
    {
        return \Heliumframework\Permalink::cpanel($url);
    }

    function webPermalink($url)
    {
        return \Heliumframework\Permalink::web($url);
    }


    function hasMenuPermission( $menu ) 
    {
        $roles = Session::get('user')['roles'];
        foreach ( $menu as $m ) {
            $permission = trim($m['permission']);
            if ( empty($permission) ) {
                return true;
            }
            if ( in_array($permission, $roles) ) {
                return true;
            }
        }
        return false;
    }
