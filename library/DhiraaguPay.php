<?php
/**
 * A class to use Vesalius Web Services
 */

class DhiraaguPay
{

    // Dhiraagu
    protected $vweb_grant_type;
    protected $vweb_user_name; 
    protected $vweb_password;
    protected $vweb_remote_server; 


    /**
     * Initialize the code
     * 
     */
    public function __construct( $grant_type, $user_name, $password, $remote_server )
    {

        // Set log files
        $this->dhiraaguLogFile = dirname(__DIR__) . '/logs/dhiraagu-logs.txt';

        try {

            if( empty($grant_type) )  { throw new Exception('Company code is required');  }
            if( empty($user_name) )   { throw new Exception('System code is required');   }
            if( empty($password) )      { throw new Exception('Password is required');      }
            if( empty($remote_server) ) { throw new Exception('Remote Server is required'); }

            $this->vweb_grant_type   = $grant_type;
            $this->vweb_user_name    = $user_name;
            $this->vweb_password     = $password;
            $this->vweb_remote_server    = $remote_server;
            // $this->auth_token          = $token;

        }
        catch( Exception $e ) {
            die($e->getMessage());
        }

    }

    public function get_access_token()
    {


        $headers = ['Content-Type: application/x-www-form-urlencoded'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->vweb_remote_server );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
                
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // In real life you should use something like:
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
                'grant_type' => $this->vweb_grant_type,
                'username' => $this->vweb_user_name,
                'password' => $this->vweb_password
               )));
        
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);

        curl_close ($ch);

        if( empty($server_output) ) {
            throw new Exception('Curl POST: No response from gateway');
        }

        $server_response = json_decode($server_output, true);
            
        $page = $server_output;
        return $page;
        exit;
    }

}
