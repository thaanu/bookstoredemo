<?php
/**
 * The main controller for API
 */
namespace Heliumframework;

use \Exception;
use Heliumframework\Auth;
use Heliumframework\Model\Audit;
use Heliumframework\Model\Application;

abstract class ApiController 
{
    protected $authKey = null, $appName = '';
    protected $errorMessage, $responseCode, $post;
    protected $response = [
        'status' => 200,
        'error' => '',
        'message' => ''
    ];

    public function __construct()
    { 

        try {
            // Authenticate Request
            // $this->authenticateApplication();

            // Log successful login audit
            $this->makeAudit('Success', 'Application logged in', 'Authentication');

            // todo: audit the requested URL

            $post = json_decode(file_get_contents("php://input"), true);
            if ( ! empty($post) ) {
                $__post = [];
                foreach ( $post as $key => $value ) {
                    $key = hfDashesToCamelCase($key);
                    $__post[$key] = $value;
                }
                $this->post = (object) $__post;
            }

        }
        catch ( Exception $ex ) {
            // Log failed audit log
            Audit::setAudit(NULL, 'API', 'Failure', 'Authentication', $ex->getCode() . ': '.$ex->getMessage());

            $this->setError($ex->getMessage(), $ex->getCode());

            $this->sendResponse();
        }

    }

    /**
     * Send form data as json
     * 
     * @return json
     */
    public function sendResponse()
    {
        header('Content-Type: application/json; charset=utf-8');
        $this->setErrorHeader($this->responseCode);
        $this->response['error'] = $this->errorMessage;
        $this->response['status'] = (empty($this->responseCode) ? 200 : $this->responseCode);
        echo json_encode($this->response);
        exit; // Do not execute anything further
    }

    protected function setError( $message, $code )
    {
        $this->responseCode = $code;
        $this->errorMessage = $message;
    }

    /**
     * Authenticate requesting application
     *
     * @return  mixed  Return true on success | Throw Exception
     */
    private function authenticateApplication()
    {
        $apacheHeaders = apache_request_headers();
        $headerKeys = array_keys($apacheHeaders);
        
        if ( ! in_array('Auth-Key', $headerKeys) ) {
            throw new Exception('Auth key was not set', 401);
        }

        $this->authKey = $apacheHeaders['Auth-Key'];

        if ( empty($this->authKey) ) {
            throw new Exception('Auth key is required', 401);
        }

        // Check if application is active in the system
        $application = new Application();
        $application = $application->selectApplicationByAuthKey( $this->authKey );

        // Throw exception if application was not found
        if ( $application['count'] == 0 ) {
            $this->throwExceptionWithAudit('Failure', 'We were unable to find your application in our system', 404, 'Authentication');
        }

        // Store application name
        $this->appName = $application['data']['app_name'];

    }

    /**
     * Sets response header
     *
     * @param   int  $errorCode  Error code
     *
     * @return  string  Return error code
     */
    private function setErrorHeader( $errorCode )
    {
        switch ( $errorCode ) {
            case 500:
                header("HTTP/1.1 500 Internal Server Error");
                break;
            case 400:
                header("HTTP/1.1 400 Bad Request");
                break;
            case 401:
                header("HTTP/1.1 401 Unauthorized");
                break;
            case 403:
                header("HTTP/1.1 403 Forbidden");
                break;
            case 404:
                header("HTTP/1.1 404 Not Found");
                break;
            case 422:
                header("HTTP/1.1 422 Unprocessable Entity");
                break;
            default:
                header("HTTP/1.1 200 OK");
        }
    }

    protected function validateMandatoryFields( array $mandatoryFields )
    {
        $dataset = (array) $this->post;
        $keys = array_keys($dataset);
        foreach ( $mandatoryFields as $mf ) {
            if ( ! in_array($mf, $keys) ) {
                throw new Exception("$mf is required", 422);
            } else {
                if ( empty($dataset[$mf]) ) {
                    throw new Exception("$mf is required", 422);
                }
            }
        }
    }

    protected function throwExceptionWithAudit( $status, $message, $errorCode, $module, $data = [] )
    {
        $this->makeAudit($status, $message, $module, $data);
        throw new \Exception($message, $errorCode);
    }

    protected function makeAudit( $status, $message, $module, $data = [] )
    {
        $username = (empty($this->authKey) ? NULL : $this->appName . ':' . $this->authKey);
        \Heliumframework\Model\Audit::setAudit($username, 'API', $status, $module, $message, json_encode($data));
    }

    /**
     * This is a helper method which creates a log in /logs/akhil.log
     * Note: This is not an audit log. Just used for debugging puroses
     *
     * @param   mixed  $content  This can be anything (string, number, array, bool)
     *
     * @return  void
     */
    protected function makeLog( $content )
    {
        (new \Heliumframework\Logging('akhil'))->debug($content);
    }

}