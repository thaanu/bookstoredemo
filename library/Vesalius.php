<?php
/**
 * A class to use Vesalius Web Services
 * NOTE: Soap Extension is required
 */

use \Heliumframework\Logging;

class Vesalius
{

    // Vesalius Webservice UAT Credentials
    protected $vweb_company_code;
    protected $vweb_system_code;
    protected $vweb_password;
    protected $vweb_remote_server;
    protected $auth_token;
    protected $dataset;
    protected $errors = [];
    public $isLogged = false;

    protected $vesaliusLogFile;
    protected $vesaliusTokensLogFile;

    protected $url_prefix;

    protected $personInformation;

    /**
     * Initialize the code
     * 
     * @param string $company_code
     * @param string $system_code
     * @param string $password
     * @param string $remote_server
     * @param boolean $devMode
     * 
     */
    public function __construct( $company_code, $system_code, $password, $remote_server, $devMode = true )
    {

        // Set log files
        $this->vesaliusLogFile = dirname(__DIR__) . '/logs/vesalius-logs.txt';
        $this->vesaliusTokensLogFile = dirname(__DIR__) . '/logs/vesalius-tokens.txt';

        try {

            if( empty($company_code) )  { throw new Exception('Company code is required');  }
            if( empty($system_code) )   { throw new Exception('System code is required');   }
            if( empty($password) )      { throw new Exception('Password is required');      }
            if( empty($remote_server) ) { throw new Exception('Remote Server is required'); }

            if( strpos($remote_server, 'https://') === false && strpos($remote_server, 'http://') === false ) {
                throw new Exception('Remote Server must start with http or https');
            }

            $this->vweb_company_code   = $company_code;
            $this->vweb_system_code    = $system_code;
            $this->vweb_password       = $password;
            $this->vweb_remote_server  = $remote_server;
            // $this->auth_token          = $token;

            // Set the remote server
            $this->url_prefix = $this->vweb_remote_server.'/tth_prod/web_services/';
    
            // Update the remote server to UAT if devMode is True
            if( $devMode == true ) {
                $this->url_prefix = $this->vweb_remote_server.'/tth_uat/web_services/';
            }

        }
        catch( Exception $e ) {
            die($e->getMessage());
        }

    }

    /**
     * Get Patient Information
     * @param string $prn
     * @return array
     */
    public function get_patient_information( $prn )
    {

        return [];

    }

    /**
     * Get all doctors
     * @return array
     */
    public function get_doctors()
    {

        return [];

    }

    /**
     * Get doctor's information
     * 
     * @param string $mcr_number
     * 
     * @return array
     */
    public function get_doctor_information( $mcr_number )
    {
        
        return [];

    }

    /**
     * Get patient queue list
     * 
     * @return object XML Object
     */
    public function get_patient_queue_list($mcr = null)
    {

        if ($mcr == null) {
            throw new Exception('MCR is required');
        }
        $response = [];

        try {

            // Check if login method was called
            if (!$this->isLogged) {
                throw new Exception("Not logged in to Vesalius");
            }


            $client         = new SoapClient($this->url_prefix . 'Patient/GetPatientQueueList.cfc?wsdl', ['trace' => 1, 'exception' => 0]);

            $response       = $client->__soapCall('getPatientQueueList', array(
                [
                    'token_number'  => $this->auth_token,
                    'mcr'           => $mcr,
                    'visit_type'    => '',
                    'company_code'  => $this->vweb_company_code,
                ]
            ));

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);
            $returnString = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $returnString);

            $response = simplexml_load_string($returnString);

            
        } catch (Exception $e) {

            // Log the error
            log_message($e->getMessage(), $this->vesaliusLogFile);
            $response = ['error' => $e->getMessage()];
        } finally {
            $this->logout();
            return $response;
        }

    }

    /**
     * Get selected doctor's roaster
     * 
     * @param string $mcr
     * @param string $start_date (DD-Mon-YYYY)
     * 
     * @return object XML Object
     */
    public function get_doctor_availability_data( $mcr, $start_date )
    {

        $response = [];

        try {

            // Check if login method was called
            if( ! $this->isLogged ) { throw new Exception("Not logged in to Vesalius"); }

            if( empty($mcr) ) { throw new Exception('MCR Number is required'); }
            if( empty($start_date) ) { throw new Exception('Start date is required'); }
            preg_match("/[0-9]+-([A-Z][a-z]+)-[0-9]*/", $start_date, $m);
            if( empty($m) ) { throw new Exception('Invalid date'); }

            $client         = new SoapClient($this->url_prefix.'Appointment/GetDoctorAvailabilityData.cfc?wsdl', ['trace' => 1, 'exception' => 0]);

            $response = $client->__soapCall('getDoctorAvailabilityData', array(
                    [
                        // 'token_number'         => $ac_token,
                        'token_number'  => $this->auth_token,
                        'mcr'           => $mcr,
                        'start_date'    => $start_date,
                        'company_code'  => $this->vweb_company_code,
                    ]
                ));

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);

            $response = simplexml_load_string($this->_cleaning_stupid_xml( $returnString ));

        } catch(Exception $e) {

            // Log the error
            (new Logging('vesalius-logs'))->error($e->getMessage());
            $response = ['error' => $e->getMessage()];

        }
        finally {
            $this->logout();
            return $response;
        }

    }

    /**
     * Create or Update a person in Vesalius
     * 
     * @param   array   $personData
     * @param   string  $operation_flag
     * 
     * @return object
     */
    public function process_person_biodata( $personData, $operation_flag = null )
    {

        $response = [];

        // Required fields for processing
        $requiredFields = ['first_name', 'middle_name', 'last_name', 'sex', 'resident', 'address1'];

        try {

            // Check if login method was called
            if( ! $this->isLogged ) { throw new Exception("Not logged in to Vesalius"); }

            // Check for operation flag
            if( $operation_flag == null || ( $operation_flag != 'U' && $operation_flag != 'I' ) ) { throw new Exception('Operation Flag is required, must be either Insert (I) or Update (U)'); }

            // Check if authentication token was created and set
            if( empty($this->auth_token) ) { throw new Exception('Authentication token not found'); }
            
            // Validate required fields
            foreach( $requiredFields as $key ) {
                if( array_key_exists($key, $personData) == false ) {
                    throw new Exception("Unable to find $key");
                }
            }

            $client         = new SoapClient($this->url_prefix.'Patient/ProcessPersonBiodata.cfc?wsdl', ['trace' => 1, 'exception' => 0]);

            $response       = $client->__soapCall('processPersonBiodata', array(
                    [
                        'token_number'      => $this->auth_token,
                        'first_name'        => $personData['first_name'],
                        'middle_name'       => $personData['middle_name'],
                        'last_name'         => $personData['last_name'],
                        'contact'           => $personData['contact'],
                        'sex'               => $personData['sex'],
                        'resident'          => $personData['resident'],
                        'dob'               => $personData['dob'],
                        'address1'          => $personData['address1'],
                        'charge_category'   => $personData['charge_category'],
                        'payment_class'     => $personData['payment_class'],
                        'operation_flag'    => $operation_flag,
                        'company_code'      => $this->vweb_company_code
                    ]
                ));

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);

            $response = simplexml_load_string($returnString);

            $this->personInformation = $personData;

            $this->documentInformation = $response->Person->Document;

        } catch(Exception $e) {

            log_message($e->getMessage(), $this->vesaliusLogFile);
            $response = ['error' => $e->getMessage()];
            
            // For this method we are logging out of Vesalius due to an error,
            // The next process will be using the same token to continue the process
            $this->logout();

        }
        finally {
            return $response;
        }

    }

    /**
     * Process patient biodata
     * 
     * @param array $params
     * @param string $operation_flag
     * 
     * @return object
     */
    public function process_patient_biodata( $params, $operation_flag = 'I' )
    {

        $response = [];

        // Required fields for processing
        $requiredFields = ['charge_category', 'payment_class'];

        try {

            // Check if authentication token was created and set
            if( empty($this->auth_token) ) { throw new Exception('Authentication token not found'); }
            
            // Validate required fields
            foreach( $requiredFields as $key ) {
                if( array_key_exists($key, $params) == false ) {
                    throw new Exception("Unable to find $key");
                }
            }

            // Get country information
            $country = (new Countries())->select($this->personInformation['country_id']);

            $client         = new SoapClient($this->url_prefix.'Patient/ProcessPatientBiodata.cfc?wsdl', ['trace' => 1, 'Exception' => 1]);

            $response       = $client->__soapCall('processPatientBiodata', array(
                    [
                        'token_number'          => $this->auth_token,
                        'company_code'          => $this->vweb_company_code,
                        'prn'                   => ($operation_flag == 'I' ? '' : $this->documentInformation->Value),
                        'document_type'         => 'ID',
                        'document_no'           => $this->personInformation['nid'],
                        'dob'                   => $this->personInformation['dob'],
                        'title'                 => '',
                        'first_name'            => $this->personInformation['first_name'],
                        'middle_name'           => $this->personInformation['middle_name'],
                        'last_name'             => $this->personInformation['last_name'],
                        'nationality'           => $this->personInformation['nationality'],
                        'resident'              => ( $this->personInformation['nationality'] == 'MV' ? 'Y' : 'N' ),
                        'sex'                   => $this->personInformation['sex'],
                        'contact'               => $this->personInformation['contact'],
                        'address1'              => $this->personInformation['address1'],
                        'address2'              => '',
                        'address3'              => '',
                        'postal_code'           => '',
                        'city_state'            => '',
                        'country_code'          => $country['country_init'],
                        'charge_category'       => $params['charge_category'],
                        'payment_class'         => $params['payment_class'],
                        'operation_flag'         => $operation_flag,
                        'other_document_type'   => '',
                        'other_document_number' => '',
                        'image_ref_no'          => '',
                        'workstation_code'      => ''
                    ]
                ));

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);

            $response = simplexml_load_string($returnString);

        } catch(Exception $e) {

            $response = ['error' => $e->getMessage()];

            // For this method we are logging out of Vesalius due to an error,
            // The next process will be using the same token to continue the process
            $this->logout();

        }
        finally {
            return $response;
        }

    }

    /**
     * Get specialty data
     *
     * @return  object 
     */
    public function getSpecialtyData()
    {

        $response = [];

        try {

            // Check if login method was called
            if( ! $this->isLogged ) { throw new Exception("Not logged in to Vesalius"); }

            $client         = new SoapClient($this->url_prefix.'Appointment/GetSpecialtyData.cfc?wsdl', ['trace' => 1, 'exception' => 0]);

            $response       = $client->__soapCall('getSpecialtyData', array(
                    [
                        'token_number'      => $this->auth_token,
                        'company_code'      => $this->vweb_company_code
                    ]
                ));

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);
            $returnString = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $returnString);

            $response = simplexml_load_string($returnString);

        }
        catch( Exception $e ) {

            // Log the error
            log_message($e->getMessage(), $this->vesaliusLogFile);
            $response = ['error' => $e->getMessage()];

        }
        finally {
            $this->logout();
            return $response;
        }

    }

    /**
     * Get reports
     *
     * @return  object          Return JSON response
     */
    public function getInvestigationReport($account_no)
    {
        
        try {

            // Check if login method was called
            if( ! $this->isLogged ) { throw new Exception("Not logged in to Vesalius"); }

            if( $account_no == null ) { throw new Exception("Account No is required"); }
       
            $client         = new SoapClient($this->url_prefix.'Clinical/GetInvestigationReport.cfc?wsdl', ['trace' => 1, 'exception' => 0]);

            $response       = $client->__soapCall('GetInvestigationReport', array(
                [
                    'token_no'              => $this->auth_token,
                    'company_code'          => $this->vweb_company_code,
                    'investigation_type'    => "ALL",
                    'account_no'            => $account_no,
                    'prn'                   => "",
                    'date'                  => "",
                ]
            ));

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);

            preg_match_all('/(?=<[^=]+?>)(?=<\/?\w+\s+\w+)(<.*?)(\s+)(.*?>)/', $returnString, $tagCollecter);

            if( !empty($tagCollecter) ) {
                foreach( $tagCollecter[0] as $tag ) {
                    $fixedTag = str_replace(' ', '', $tag);
                    $returnString = str_replace($tag, $fixedTag, $returnString);
                }
            }

            // Replace & with &amp;
            $returnString = str_replace('&', '&amp;', $returnString);
            $returnString = str_replace('nbsp;', '', $returnString);

            $response = simplexml_load_string($returnString);

        }
        catch( Exception $e ) {
            // Log the error
            log_message($e->getMessage(), $this->vesaliusLogFile);
            $response = ['error' => $e->getMessage()];
        }
        finally {
            $this->logout();
            return $response;
        }

    }

    /**
     * Get next available slots
     * 
     * @param array $param Parameters - Requried ['prn', 'specialty_code', 'mcr', 'start_date', 'start_time', 'case_type' => "NC or FU"]
     *
     * @return  object
     */
    public function getNextAvailableSlots( $params )
    {

        $requiredFields = ['prn', 'speciality_code', 'start_date'];
        $response = [];

        try {

            foreach( $requiredFields as $key ) {
                if( array_key_exists($key, $params) == false ) {
                    throw new Exception("$key is required");
                }
            }

            if( !isset($params['case_type']) ) {
                $params['case_type'] = 'NC';
            }

            $client         = new SoapClient($this->url_prefix.'Appointment/GetNextAvailableSlots.cfc?wsdl', ['trace' => 1, 'exception' => 0]);

            $response       = $client->__soapCall('getNextAvailableSlots', array(
                    [
                        'token_number'          => $this->auth_token,
                        'company_code'          => $this->vweb_company_code,
                        'prn'                   => $params['prn'],
                        'specialty_code'        => $params['speciality_code'],
                        'mcr'                   => ( isset($params['mcr']) ? $params['mcr'] : '' ),
                        'start_date'            => $params['start_date'],
                        'start_time'            => ( isset($params['start_time']) ? $params['start_time'] : '' ),
                        'case_type'             => $params['case_type']
                    ]
                ));

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);
            $returnString = str_replace('&', '&amp;', $returnString);
            $response = simplexml_load_string($returnString);
            
            // Check for errors
            if( isset($response->Error) ) {
                throw new Exception($this->error_messages(trim($response->Error->ErrorCode)));
            }

            if( $response == false ) {
                throw new Exception('SimpleXML Error');
            }

        }
        catch( Exception $e ) {
            log_message("Error: " . $e->getMessage(), $this->vesaliusLogFile);
        }
        finally {
            $this->logout();
            return $response;
        }

    }

    /**
     * Make an appointment
     *
     * @param   string $prn             Patient number
     * @param   string $slot_number     Appointment slot number
     * @param   string $case_type       Case Type. Accepts either NC - New Case or FU - Follow-up. Default is NC
     *
     * @return  object                  Return JSON response
     */
    public function makeAppointment( $prn = null, $slot_number = null, $case_type = 'NC' )
    {

        try {

            // Check if login method was called
            if( ! $this->isLogged ) { throw new Exception("Not logged in to Vesalius"); }

            if( $prn == null ) { throw new Exception("PRN is required"); }
            if( $slot_number == null ) { throw new Exception("Appointment slot number is required"); }

            $client         = new SoapClient($this->url_prefix.'Appointment/MakeAppointment.cfc?wsdl', ['trace' => 1, 'exception' => 0]);

            log_message("APPOINTMENTS ISSUE", dirname(__DIR__) . '/logs/appointment-issue.txt');

            $response       = $client->__soapCall('makeAppointment', array(
                    [
                        'token_number'      => $this->auth_token,
                        'company_code'      => $this->vweb_company_code,
                        'prn'               => $prn,
                        'slot_number'       => $slot_number,
                        'case_type'         => $case_type,
                        'reason_code'       => '',
                        'remark'            => ''
                    ]
                ));

                log_message(print_r($response, true), dirname(__DIR__) . '/logs/appointment-issue.txt');
            

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);

            // Clean up
            $returnString = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $returnString);

            
            if( empty($returnString) ) {
                log_message('No response from Vesalius', $this->vesaliusLogFile);
            }

            $response = simplexml_load_string($returnString);

        }
        catch( Exception $e ) {
            log_message('Vesalius Error: ' . $e->getMessage(), $this->vesaliusLogFile);
        }
        finally {
            $this->logout();
            return $response;
        }

    }

    public function changeAppointment( $prn = null, $slot_number = null, $existingAppointmentNumber = null, $reason = '', $case_type = 'NC' )
    {

        try {

            // Check if login method was called
            if( ! $this->isLogged ) { throw new Exception("Not logged in to Vesalius"); }

            if( $prn == null ) { throw new Exception("PRN is required"); }
            if( $slot_number == null ) { throw new Exception("Appointment slot number is required"); }
            if( $existingAppointmentNumber == null ) { throw new Exception("Appointment Number is required"); }
            if( $reason == null ) { throw new Exception("Reason is required"); }

            $client         = new SoapClient($this->url_prefix.'Appointment/ChangeAppointment.cfc?wsdl', ['trace' => 1, 'exception' => 0]);

            $response       = $client->__soapCall('makeAppointment', array(
                    [
                        'token_number'          => $this->auth_token,
                        'company_code'          => $this->vweb_company_code,
                        'prn'                   => $prn,
                        'slot_number'           => $slot_number,
                        'appointment_number'    => $existingAppointmentNumber,
                        'reason'                => $reason
                    ]
                ));

                log_message(print_r($response, true), dirname(__DIR__) . '/logs/appointment-issue.txt');
            

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);

            // Clean up
            $returnString = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $returnString);

            
            if( empty($returnString) ) {
                log_message('No response from Vesalius', $this->vesaliusLogFile);
            }

            $response = simplexml_load_string($returnString);

        }
        catch( Exception $e ) {
            log_message('Vesalius Error: ' . $e->getMessage(), $this->vesaliusLogFile);
        }
        finally {
            $this->logout();
            return $response;
        }

    }

    /**
     * Process appointment booking fee
     *
     * @param   string      $prn
     * @param   string      $appointment_no
     * @param   string      $booking_fee
     * @param   boolean     $logout
     *
     * @return  object                  Return JSON response
     */
    public function process_appointment_booking_fee( $prn, $appointment_no, $booking_fee, $payment_code = 'GUESTPORTAL', $logout = true )
    {

        try {

            // Loop all the appointments
            if( empty($prn) )               { throw new Exception('PRN is missing');                }
            if( empty($appointment_no) )    { throw new Exception('appointment_number is required'); }
            if( empty($booking_fee) )       { throw new Exception('booking_fee is required');        }

            $client         = new SoapClient($this->url_prefix.'Appointment/ProcessAppointmentBookingFee.cfc?wsdl', ['trace' => 1, 'exception' => 0]);

            $response       = $client->__soapCall('processAppointmentBookingFee', array(
                [
                    'token_number'          => $this->auth_token,
                    'company_code'          => $this->vweb_company_code,
                    'prn'                   => $prn,
                    'appointment_ref_no'    => $appointment_no,
                    'amount'                => $booking_fee,
                    'payment_code'          => $payment_code
                ]
            ));

            log_message(print_r($response, true), $this->vesaliusLogFile);
            log_message(print_r($response, true), dirname(__DIR__) . '/logs/process_appointment_booking_fee.txt');

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);

            $response = simplexml_load_string($returnString);

        }
        catch( Exception $e ) {
            log_message('Vesalius Error (PROCESS APPOINTMENT BOOKING FEE): ' . $e->getMessage(), $this->vesaliusLogFile);
        }
        finally {
            if( $logout ) { $this->logout(); }
            return $response;
        }

    }

    /**
     * Cancel an appointment
     *
     * @param   string $prn                     Patient number
     * @param   string $appointment_number      Appointment slot number
     * @param   string $reason                  A reason why the appointment was cancelled
     * @param   boolean $logout                Mark true to logout after 
     *
     * @return  object          Return JSON response
     */
    public function cancelAppointment( $prn = null, $appointment_number = null, $reason = null, $logout = true )
    {

        try {

            if( $prn == null ) { throw new Exception("PRN is required"); }
            if( $appointment_number == null ) { throw new Exception("Appointment number is required"); }
            if( $reason == null ) { throw new Exception("Reason is required"); }

            $client         = new SoapClient($this->url_prefix.'Appointment/CancelAppointment.cfc?wsdl', ['trace' => 1, 'exception' => 1]);

            $response       = $client->__soapCall('cancelAppointment', array(
                    [
                        'token_number'          => $this->auth_token,
                        'company_code'          => $this->vweb_company_code,
                        'prn'                   => $prn,
                        'appointment_number'    => $appointment_number,
                        'reason'                => $reason
                    ]
                ));

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);

            $response = simplexml_load_string($returnString);

            return $response;

        }
        catch( SoapFault $e ) {
            log_message("Soap Fault. " . $e->getMessage(), $this->vesaliusLogFile);
        }
        catch( Exception $e ) {
            log_message("Error and logged out. " . $e->getMessage(), $this->vesaliusLogFile);
        }
        finally {
            if( $logout ) { $this->logout(); }
        }

    }

    /**
     * Get patient past visits
     *
     * @param   string $prn                     Patient number
     * @param   string $appointment_number      Appointment slot number
     * @param   string $reason                  A reason why the appointment was cancelled
     *
     * @return  object          Return JSON response
     */
    public function getPatientPastVisits( $prn = null, $no_of_past_visits = 1 )
    {

        try {

            if( $prn == null ) { throw new Exception("PRN is required"); }
            if( is_numeric($no_of_past_visits) == false ) { throw new Exception("no_of_past_visits must be a number, and greater than 0"); }
            if( $no_of_past_visits == 0 ) { throw new Exception("no_of_past_visits must be greater than 0"); }

            $client         = new SoapClient($this->url_prefix.'Patient/GetPatientPastVisits.cfc?wsdl', ['trace' => 1, 'exception' => 1]);

            $response       = $client->__soapCall('getPatientPastVisits', array(
                    [
                        'token_number'          => $this->auth_token,
                        'company_code'          => $this->vweb_company_code,
                        'prn'                   => $prn,
                        'past_visits'           => $no_of_past_visits
                    ]
                ));

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);

            preg_match_all('/(?=<[^=]+?>)(?=<\/?\w+\s+\w+)(<.*?)(\s+)(.*?>)/', $returnString, $tagCollecter);

            if( !empty($tagCollecter) ) {
                foreach( $tagCollecter[0] as $tag ) {
                    $fixedTag = str_replace(' ', '', $tag);
                    $returnString = str_replace($tag, $fixedTag, $returnString);
                }
            }

            // Replace & with &amp;
            $returnString = str_replace('&', '&amp;', $returnString);

            $response = simplexml_load_string($returnString);

            return $response;

        }
        catch( SoapFault $e ) {
            log_message("Soap Fault. " . $e->getMessage(), $this->vesaliusLogFile);
        }
        catch( Exception $e ) {
            log_message("Error and logged out. " . $e->getMessage(), $this->vesaliusLogFile);
        }
        finally {
            $this->logout();
        }

    }

    

    /**
     * Login - Authentication
     * 
     * @return boolean
     */
    public function login()
    {

        try {

            $client         = new SoapClient($this->url_prefix.'Authentication/Login.cfc?wsdl', ['trace' => 1, 'exception' => 0]);
    
            $response       = $client->__soapCall('login', array(
                                [
                                    'company_code' => $this->vweb_company_code,
                                    'system_code' => $this->vweb_system_code,
                                    'password' => $this->vweb_password
                                ]
                            ));
    
            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);
            $xml = simplexml_load_string($returnString);
    
            // Check for errors
            if( isset($xml->Error) ) {
                throw new Exception($xml->Error->ErrorMessage);
            }
            // If no errors
            else {
                $this->auth_token = $xml->Token->Token_number;

                // Store tokens 
                log_message( $this->auth_token . ' -> Logged In', $this->vesaliusTokensLogFile );

                // Flag as logged in to vesalius
                $this->isLogged = true;

            }

        }
        catch( Exception $e ) {
            die($e->getMessage());
        }
        finally {
            sleep(3);
            return true;
        }

    }

    /**
     * Logout - Authentication
     * 
     * @param string $access_token (optional)
     * 
     * @return boolean
     */
    public function logout( $access_token = null )
    {
        if( $this->isLogged ) {
    
            $ac_token = ( $access_token == null ? $this->auth_token : $access_token );

            try {

                $client         = new SoapClient($this->url_prefix.'Authentication/Logout.cfc?wsdl', ['trace' => 1, 'exception' => 0]);
                $response       = $client->__soapCall('logout', array(['token_number' => $ac_token]));

                $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);
                $xml = simplexml_load_string($returnString);

                // Check for errors
                if( isset($xml->Error) ) {
                    throw new Exception($xml->Error->ErrorMessage);
                }
                // If no errors
                else {
                    // Mark token as logged out
                    log_message( $ac_token . ' -> Logged Out', dirname(__DIR__) . '/logs/vesalius-tokens.txt' );

                    $this->auth_token = ''; // Reset token

                    // Mark as vesalius session ended
                    $this->isLogged = false;

                    return true;
                }

            }
            catch(Exception $e) {
                log_message("Caught Exception :" . $e->getMessage(), $this->vesaliusTokensLogFile);
                return false;
            }
        }
    }

    public function manual_logout( $access_token = null )
    {

        try {

            $client         = new SoapClient($this->url_prefix.'Authentication/Logout.cfc?wsdl', ['trace' => 1, 'exception' => 0]);
            $response       = $client->__soapCall('logout', array(['token_number' => $access_token]));

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);
            $xml = simplexml_load_string($returnString);

            // Check for errors
            if( !empty($xml->Error) ) {
                throw new Exception($xml->Error->ErrorMessage);
            }
            
            // Mark token as logged out
            log_message( $access_token . ' -> Logged Out', $this->vesaliusTokensLogFile );

            return true;

        }
        catch(Exception $e) {

            if( trim($e->getMessage()) == 'TOKEN NUMBER HAS BEEN ALREADY LOGGED OUT' ) {
                log_message( $access_token . ' -> Logged Out', $this->vesaliusLogFile );
            }

            if( trim($e->getMessage()) == 'TOKEN NUMBER DOES NOT EXIST IN THE SYSTEM' ) {
                log_message( $access_token . ' -> Logged Out', $this->vesaliusLogFile );
            }
            
            log_message("Caught Exception : Token $access_token\t" . $e->getMessage(), $this->vesaliusLogFile);
        }
    }

    /**
     * Return the current access token
     * @return string
     */
    public function get_auth_token()
    {
        return $this->auth_token;
    }

    /**
     * Get vesalius error messages
     * @param string $error_code
     * @return string
     */
    public function error_messages( $error_code )
    {

        $messages = [
            'WS-00001' => 'ORGANIZATION INFORMATION IS MANDATORY',
            'WS-00002' => 'CODE INFORMATION IS MANDATORY',
            'WS-00003' => 'PASSWORD INFORMATION IS MANDATORY',
            'WS-00004' => 'ORGANIZATION DOES NOT EXIST IN THE SYSTEM PLEASE CHECK',
            'WS-00005' => 'AUTHENTICATION FAILED',
            'WS-00006' => 'MAXIMUM NUMBER OF CONNECTIONS REACHED',
            'WS-00007' => 'DATABASE ERROR',
            'WS-00018' => 'GIVEN DATE IS PAST DATE',
            'WS-00019' => 'INVALID DATE FORMAT',
            'WS-00039' => 'SUCCESSFULLY LOGGED OUT',
            'WS-00138' => 'SYSTEM HAS DETECTED FREQUENT OVERLOADING OF THIS WEB SERVICE CALLS. PLEASE WAIT AND CALL AGAIN.'
        ];

        if( array_key_exists($error_code, $messages) ) {
            return $messages[$error_code];
        }

        return $error_code;

    }

    /**
     * Return person information
     * 
     * @param string $param
     *
     * @return  string 
     */
    public function person( $param )
    {   
        return $this->personInformation->$param;
    }

    /**
     * Get Patient Deposit
     *
     * @param   string      $prn
     *
     * @return  object      Return JSON response
     */
    public function get_patient_deposit( $prn )
    {

        try {

 
            if( empty($prn) )               { throw new Exception('PRN is missing');                }
       
            $client         = new SoapClient($this->url_prefix.'Patient/GetPatientDeposit.cfc?wsdl', ['trace' => 1, 'exception' => 0]);


            $response       = $client->__soapCall('GetPatientDeposit', array(
                [
                    'token_number'          => $this->auth_token,
                    'company_code'          => $this->vweb_company_code,
                    'prn'                   => $prn,
                ]
            ));

            $returnString = str_replace('<?xml version="1.0" encoding="UTF-8">', '<?xml version="1.0" encoding="UTF-8"?>', $response->return);

            $response = simplexml_load_string($returnString);

        }
        catch( Exception $e ) {
            log_message('Vesalius Error: ' . $e->getMessage(), $this->vesaliusLogFile);
        }
        finally {
            $this->logout();
            return $response;
        }

    }

    /**
     * Send a soap request
     * !! YET NOT USING
     * @param string $cfc
     * @param array $data_arr
     * @return boolean
     */
    private function _soap_yourself( $cfc, $data_arr )
    {

        $client         = new SoapClient('http://'.$this->vweb_remote_server.'/tth_prod/web_services/patient/'.ucwords($cfc).'.cfc?wsdl', ['trace' => 1, 'exception' => 0]);
        $response       = $client->__soapCall($cfc, array($data_arr));

        $xml            = simplexml_load_string($response->return);

        if( !empty($xml) ) {
            $this->dataset  = $xml;
            return true;
        }

        // By default return false
        return false;

    }

    /**
     * Check for error
     * @param string $message
     * @return boolean
     */
    private function _has_error( $message )
    {

        return ( strpos($message, 'WS-') > -1 ? true : false );

    }

    /**
     * Cleaning XML
     * @param string $string
     * @return string
     */
    private function _cleaning_stupid_xml( $string )
    {
        $output = [];
        $brokenXML = explode("\n", $string);

        $swicher = false;

        if( !empty($brokenXML) ) {
            foreach( $brokenXML as $str ) {

                // Do matching
                preg_match("/[0-9]+-([A-Z][a-z]+)-[0-9]+/", $str, $matches);
                
                if( !empty($matches) ) {

                    $x = explode('-', $matches[0]);
                    $y = $x[1] . '-' . $x[0] . '-' . $x[2];
                    $output[] = "<". ($swicher ? '/' : '') . "$y>";

                    $swicher = ( $swicher ? false : true );

                }
                else {
                    $output[] = $str;
                }

            }
        }

        return implode(' ', $output);

    }

}
