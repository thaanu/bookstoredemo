<?php 
/**
 * A class written to communicate with Aasandha Portal (Vinavi)
 * 
 * @author Ahmed Shan
 * 
 */
class Vinavi 
{
    
    protected $configurations   = [];

    protected $vinaviTokenFile  = '/token.txt';

    protected $refeshToken;
    protected $apiURLPrefix     = 'https://api.aasandha.mv';
    protected $authURL          = 'https://auth.aasandha.mv/oauth/access_token';

    public function __construct( $configurations = [] )
    {

        try {

            $requiredVars = ['vinavi_directory', 'client_id', 'client_secret', 'grant_type', 'user_id', 'service_provider', 'api_version'];

            if( empty($configurations) || ! is_array($configurations) ) {
                throw new Exception("Please fill in the required configurations. " . implode(", ", $requiredVars));
            }

            // Check if vinavi directory exists, else create a directory
            if( is_dir($configurations['vinavi_directory']) == false ) {

                if(mkdir($configurations['vinavi_directory'], 0775) == false) {
                    throw new Exception("Unable to create directory for vinavi.");
                }
                
                if(touch($configurations['vinavi_directory'] . $this->vinaviTokenFile) == false){
                    throw new Exception('Unable to create a token file for vinavi.' . $configurations['vinavi_directory'] . $this->vinaviTokenFile);
                }

            }

            foreach( $requiredVars as $v ) {
                if( array_key_exists($v, $configurations) == false ) {
                    throw new Exception("Unable to find $v in configuration");
                }
            }
            
            // Store configuration file
            $this->configurations = $configurations;

            // Authenticate Application
            $this->authenticate();

        }
        catch( Exception $e ) {
            die($e->getMessage());
        }
        
    }

    /**
     * Authenticate and get a refresh token
     * 
     * @return object   JSON Response
     */
    public function authenticate()
    {

        // Check if current authentication token expired
        $tokenInformation = json_decode(file_get_contents($this->configurations['vinavi_directory'] . $this->vinaviTokenFile), true);
        if( empty($tokenInformation) ) {

            $postFields = [
                'client_id'     => $this->configurations['client_id'],
                'client_secret' => $this->configurations['client_secret'],
                'grant_type'    => $this->configurations['grant_type']
            ];
    
            $this->storeToken($this->authRequest($postFields));

        }


    }

    /**
     * A method for storing token in a local file for reuse
     *
     * @param   string  $content  JSON String
     *
     * @return  boolean            
     */
    public function storeToken( $content )
    {
        return (file_put_contents($this->configurations['vinavi_directory'] . $this->vinaviTokenFile, $content) ? true : false);
    }

    /**
     * Get the stored token
     * 
     * @return string
     */
    public function getToken()
    {
        return json_decode(file_get_contents($this->configurations['vinavi_directory'] . $this->vinaviTokenFile), true)['access_token'];
    }

    /**
     * Search patient information by National ID Card Number
     *
     * @param   string  $nid  National ID card number
     *
     * @return  array
     */
    public function searchPatientByNIC( $nid )
    {

        $results = json_decode($this->get($this->apiURLPrefix . "/patients/search/$nid"), true);

        if( isset($results['data']) ) {

            return [
                'type'      => $results['data']['type'],
                'id'        => $results['data']['id'],
                'nic'       => $results['data']['attributes']['national_identification'],
                'name'      => $results['data']['attributes']['patient_name'],
                'dob'       => $results['data']['attributes']['birth_date'],
                'gender'    => $results['data']['attributes']['gender'],
                'phone'     => $results['data']['attributes']['phone'],
                'email'     => $results['data']['attributes']['email'],

                'address'   => $this->getAddress( $results['data']['relationships']['address']['data']['id'] ),

                'photo'     => $this->getProfilePhoto($results['data']['id'])
            ];

        }

        return [];

    }

    /**
     * Get patient photo
     *
     * @param   int  $id  Patient ID provided by Aasandha
     *
     * @return  array          JSON Response
     */
    public function getProfilePhoto( $id )
    {

        $results = json_decode($this->get( $this->apiURLPrefix . "/patients/$id/photo" ), true);

        if( isset($results['data']) ) {

            return [
                'id'            => $results['data']['id'],
                'photo'         => $results['data']['attributes']['photo'],
                'created_at'    => $results['data']['attributes']['created_at'],
                'updated_at'    => $results['data']['attributes']['updated_at']
            ];

        }

        return [];

    }

    /**
     * Get address information by ID
     *
     * @param   int  $id  ID provided by Aasandha
     *
     * @return  array       
     */
    public function getAddress( $id )
    {

        $results = json_decode($this->get( $this->apiURLPrefix . "/addresses/$id" ), true);

        if( isset($results['data']) ) {

            // Fetch island
            $island = $this->getIsland($results['data']['relationships']['island']['data']['id']);

            return [
                'id'            => $results['data']['id'],
                'house_name'    => $results['data']['attributes']['address_line_one'],
                'longitude'     => $results['data']['attributes']['longitude'],
                'latitude'      => $results['data']['attributes']['latitude'],
                'island'        => $island
            ];

            return $results;

        }

        return [];

    }

    /**
     * Get the island by ID
     *
     * @param   int  $id  ID provided by Aasandha
     *
     * @return  array       
     */
    public function getIsland( $id )
    {

        $results = json_decode($this->get( $this->apiURLPrefix . "/islands/$id" ), true);

        if( isset($results['data']) ) {

            return [
                'id'            => $results['data']['id'],
                'latin_name'    => $results['data']['attributes']['latin_name'],
                'dhivehi_name'  => $results['data']['attributes']['dhivehi_name'],
                'longitude'     => $results['data']['attributes']['longitude'],
                'latitude'      => $results['data']['attributes']['latitude']
            ];

        }

        return [];

    }

    /**
     * Send an get request
     *
     * @param   string  $url  Request URL
     *
     * @return  object        JSON object
     */
    protected function get( $url )
    {

        return $this->sendRequest( $url );

    }

    /**
     * Send a post request
     *
     * @param   string  $url  Request URL
     * @param   array  $dataset  Key Value pair of data
     *
     * @return  object        JSON object
     */
    protected function post( $url, $dataset = [] )
    {

        return $this->sendRequest( $url, $dataset, 'POST' );

    }

    /**
     * Send an update request
     *
     * @param   string  $url  Request URL
     * @param   array   $dataset  Fields and Values to update (Key and Values)
     *
     * @return  object        JSON object
     */
    protected function patch( $url, $dataset = [] )
    {

        return $this->sendRequest( $url, $dataset, 'PATCH' );

    }

    /**
     * Send an delete request
     *
     * @param   string  $url  Request URL
     *
     * @return  object        JSON object
     */
    protected function delete( $url )
    {

    }

    /**
     * Send Request
     *
     * @param string $url
     * @param array $dataset
     * @param string $method by default is GET
     * 
     * @return object JSON Response
     */
    protected function sendRequest( $url, $dataset = [], $method = 'GET' )
    {

        try {

            $ch = curl_init();
    
            curl_setopt($ch, CURLOPT_URL, $url);
    
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

            if( $method == 'POST' || $method == 'PATCH' ) {
                if( !empty($dataset) ) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataset) );
                }
            }

            curl_setopt($ch, CURLOPT_HTTPHEADER, [      
                    'Content-Type: application/vnd.api+json',
                    'Authorization: Bearer ' . $this->getToken(),
                    'Api-Version:' . $this->configurations['api_version'],
                    'Service-Provider: ' . $this->configurations['service_provider'],
                    'User: '.$this->configurations['user_id']
                ]                                                         
            );

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,    FALSE);
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            $server_output = curl_exec($ch);
    
            curl_close ($ch);

            return $server_output;

        }
        catch( Exception $e ) {
            die($e->getMessage());
        }

    }

    /**
     * Send an auth request without headers
     *
     * @param   array  $dataset  post content
     *
     * @return  object                JSON Response
     */
    public function authRequest( $dataset = [] )
    {

        try {

            $ch = curl_init();
    
            curl_setopt($ch, CURLOPT_URL, $this->authURL);
    
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

            if( empty($dataset) ) {
                throw new Exception("No post fields set");
            }

            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataset) );

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,    FALSE);
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            $server_output = curl_exec($ch);
    
            curl_close ($ch);

            return $server_output;

        }
        catch( Exception $e ) {
            die($e->getMessage());
        }

    }

}