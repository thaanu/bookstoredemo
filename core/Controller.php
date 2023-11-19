<?php
/**
 * The main controller file
 */
namespace Heliumframework;

use Jenssegers\Blade\Blade;
use PHPMailer\PHPMailer\Exception;
use Heliumframework\Auth;

abstract class Controller 
{

    private $blade;
    public $formData;
    public $formResponse = [
        'status' => 400,
        'errors' => [],
        'error' => '',
        'error_fields' => [],
        'textMessage' => ''
    ];

    public function __construct()
    { 

        // Unserialize FormData from $_POST['data'] to an array
        if( isset($_POST['data']) ) {
            
            parse_str($_POST['data'], $this->formData);

            // Check whether csrf was set
            if(
                isset($this->formData['csrf']) == false ||
                (isset($this->formData['csrf']) && Session::get('csrf') != $this->formData['csrf']) ||
                \Heliumframework\Session::get('csrf') == false
            ) {
                $this->formResponse['errors'][] = 'Error 419';
                $this->sendJsonResponse();
            }

        } else {
            // Handle raw $_POST
            if ( isset($_POST) && $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $this->formData = $_POST;
            }
        }


        // Initialize Blade
        $this->blade = new \Jenssegers\Blade\Blade(dirname(__DIR__).'/views', dirname(__DIR__).'/cache');

    }

    /**
     * Render View
     * @param string $viewname
     * @param array $data
     */
    public function view( $viewname, $data = [] )
    {

        try {

            // Check if view exists
            $checkViewName = str_replace('.', '/', $viewname);
            $viewPath = dirname(__DIR__)."/views/$checkViewName.blade.php";
            if ( file_exists($viewPath) == false ) { throw new Exception("View name <b>$viewname</b> did not found"); }

            // Render View
            echo $this->blade->make($viewname, $data)->render();

        }
        catch ( Exception $e ) {
            (new Logging('view_errors'))->error('Unable to find view '.$e->getMessage().'. Request URI: ' . $_SERVER['REQUEST_URI']);
            echo '<div style="font-family: helvetica; text-align: center; border: 1px solid #eee; padding: 20px; margin: 0 auto; width: 60%; border-radius: 20px;">';
            echo '<p><span style="color: red; font-weight: 800; text-transform: uppercase;">Whoops!</span><br>';
            echo $e->getMessage();
            echo '</p>';
            echo '</div>';
        }

    }

    public function returnView( $viewname, $data = [] )
    {

        try {

            // Check if view exists
            $checkViewName = str_replace('.', '/', $viewname);
            $viewPath = dirname(__DIR__)."/views/$checkViewName.blade.php";
            if ( file_exists($viewPath) == false ) { throw new Exception("View name <b>$viewname</b> did not found"); }

            // Render View
            return $this->blade->make($viewname, $data)->render();

        }
        catch ( Exception $e ) {
            (new Logging('view_errors'))->error('Unable to find view '.$e->getMessage().'. Request URI: ' . $_SERVER['REQUEST_URI']);
            return '<div style="font-family: helvetica; text-align: center; border: 1px solid #eee; padding: 20px; margin: 0 auto; width: 60%; border-radius: 20px;">
            <p><span style="color: red; font-weight: 800; text-transform: uppercase;">Whoops!</span><br>'.$e->getMessage(). '</p></div>';
        }

    }

    public function setError( $message, $code )
    {
        $this->formResponse['error'] = $message;
        $this->formResponse['status'] = $code;
    }

    /**
     * Send form data as json
     * @return json
     */
    public function sendJsonResponse()
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->formResponse);
        exit; // Do not execute anything further
    }

    /**
     * Authenticate View
     * @param string $permission
     * @return void
     */
    public function viewAuthenticate( $permission )
    {
        if( Auth::hasPermission($permission) == false ) {
            // Show 40# Unauthorised
            error_header(401);
        }
    }

    /**
     * Authenticate whether user has permission
     * @param string $permission
     * @return object
     */
    public function ajaxAuthentication( $permission )
    {
        if( Auth::hasPermission($permission) == false ) {
            $this->formResponse['errors'][] = 'Sorry, you do not have permission to perform this action';
            $this->sendJsonResponse();
        }
    }

    protected function throwExceptionWithAudit( $status, $message, $module, $actionLevel, $data = [] )
    {
        \Heliumframework\Model\Audit::setAudit(NULL, $actionLevel, $status, $module, $message, json_encode($data));
        throw new \Exception($message);
    }

}