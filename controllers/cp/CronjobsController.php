<?php
/**
 * Cronjobs Controller
 * @author Ahmed Shan (@thaanu16)
 * 
 */

use Heliumframework\Auth;
use Heliumframework\Controller;
use Heliumframework\Logging;
use Heliumframework\Model\Cronjobs;
class CronjobsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        Auth::userLogged();
    }

    public function index( $page = 1, $limit = 10 )
    {  

        // Authenticate User
        $this->viewAuthenticate( 'MNG_CRONJOBS' );

        // Render View
        $this->view('admin-panel.cronjobs.main');
        
    }

    public function ajaxHandler( $action = '', $param = '' )
    {
        try {

            $this->ajaxAuthentication( 'MNG_CRONJOBS' );

            if ( $action == 'fetch-data' ) {
                $cronjobs = (new Cronjobs())->selectAll();
                $this->formResponse['status'] = 200;
                $this->formResponse['view'] = $this->returnView('admin-panel.cronjobs._data', [
                    'data' => $cronjobs
                ]);
            }
            else {
                throw new Exception('Invalid Action', 400);
            }

        }
        catch ( Exception $ex ) {
            $this->formResponse['error'] = $ex->getMessage();
        }
        finally {
            $this->sendJsonResponse();
        }
    }

}