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
        $this->view('applications.main', [
            'applications' => (new Application())->selectAll()
        ]);
    }

    public function create()
    {
        $this->view('applications.create');
    }

    public function store()
    {
        try {

            $formReq = [
                'app_name' => [
                    'required' => true,
                    'label' => 'Application Name'
                ]
            ];

            $validate = new Validate();
            $validation = $validate->check($this->formData, $formReq);

            if ( ! $validation->passed() ) {
                $this->formResponse['error_fields'] = $validation->errors();

                // Audit
                $msg = 'Please check required fields';
                Audit::setAudit(NULL, 'User', 'Failure', 'Applications', $msg, json_encode($this->formData));

                throw new Exception($msg);
            }

            $appAuthKey = md5(time());

            $application = new Application();
            $application->setPayload('app_name', $this->formData['app_name']);
            $application->setPayload('app_auth_key', $appAuthKey);
            $application->setPayload('app_auth_dt', date('Y-m-d H:i:s'));

            if ( ! $application->store() ) {

                // Audit
                $msg = 'Unable to create new application';
                Audit::setAudit(NULL, 'User', 'Failure', 'Applications', $msg, json_encode($this->formData));

                throw new Exception($msg);
            }

            Audit::setAudit(NULL, 'User', 'Success', 'Applications', "Created application " . $this->formData['app_name'], json_encode($this->formData));

            $this->formResponse = [
                'status' => true,
                'textMessage' => 'Application created'
            ];

        }
        catch ( Exception $ex ) {
            $this->formResponse['error'] = $ex->getMessage();
        }
        finally {
            $this->sendJsonResponse();
        }
    }

    public function destroy( $appId = null )
    {
        try {

            if ( empty($appId) ) {
                $this->throwExceptionWithAudit("Failure", "AppId is required", "Applications", "User");
            }

            $application = new Application( $appId );
            $theApplication = $application->select();

            if ( $theApplication['count'] == 0 ) {
                $this->throwExceptionWithAudit("Failure", "Application does not exist", "Applications", "User");
            }

            $removeApp = new Application( $appId );

            if ( ! $removeApp->delete() ) {
                // Audit
                $this->throwExceptionWithAudit("Failure", "Unable to revoke " . $theApplication['data']['app_name'], "Applications", "User");
            }

            Audit::setAudit(NULL, 'User', 'Success', 'Applications', "Application " . $theApplication['data']['app_name'] . " revoked successfully", json_encode($theApplication));

            $this->formResponse = [
                'status' => true,
                'textMessage' => 'Application revoked'
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