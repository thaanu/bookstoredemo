<?php
/**
 * Applications Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\Controller;
use Heliumframework\Auth;
use Heliumframework\Validate;
use Heliumframework\Model\Application;
use Heliumframework\Model\Audit;

class ApplicationsController extends Controller {

    public function __construct()
    {
        parent::__construct();
        Auth::userLogged();
    }

    public function index()
    {
        $this->view('admin-panel.applications.main', [
            'applications' => (new Application())->selectAll()
        ]);
    }

    public function create()
    {
        $this->view('admin-panel.applications.create');
    }

    public function store()
    {
        try {

            $formReq = [
                'client_name' => [
                    'required' => true,
                    'label' => 'Client Name'
                ]
            ];

            $validate = new Validate();
            $validation = $validate->check($this->formData, $formReq);

            if ( ! $validation->passed() ) {
                $this->formResponse['error_fields'] = $validation->errors();
                throw new Exception('Please check required fields');
            }

            $appAuthKey = md5(time());

            $application = new Application();
            $application->setPayload('client_name', $this->formData['client_name']);
            $application->setPayload('client_key', $appAuthKey);

            if ( ! $application->store() ) {
                throw new Exception('Unable to create new application');
            }

            $this->formResponse = [
                'status' => true,
                'textMessage' => 'Client created'
            ];

        }
        catch ( Exception $ex ) {
            $this->setError( $ex->getMessage(), $ex->getCode() );
        }
        finally {
            $this->sendJsonResponse();
        }
    }

    public function destroy( $appId = null )
    {
        try {

            if ( empty($appId) ) {
                throw new Exception('Client not available', 404);
            }

            $application = new Application( $appId );
            $theApplication = $application->selectApplicationById();

            if ( $theApplication['count'] == 0 ) {
                throw new Exception('Client not available', 404);
            }

            $removeApp = new Application( $appId );

            if ( ! $removeApp->delete() ) {
                // Audit
                throw new Exception('Unable to revoke client', 404);
            }

            $this->formResponse = [
                'status' => 200,
                'textMessage' => 'Client revoked'
            ];

        }
        catch ( Exception $ex ) {
            $this->formResponse['error'] = $ex->getMessage();
        }
        finally {
            $this->sendJsonResponse();
        }
    }

}