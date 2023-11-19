<?php

/**
 * Documentation Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\Controller;
use Heliumframework\Auth;
use Heliumframework\Model\Device;

class DocumentationController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        Auth::userLogged();
    }

    public function index()
    {
        $this->view('admin-panel.documentation.main');
    }

    public function topic( string $topic )
    {
        try {
            $this->formResponse = [
                'status' => 200, 
                'view' => $this->returnView('admin-panel.documentation._'.$topic)
            ];
        }
        catch ( Exception $ex ) {
            $this->setError( $ex->getMessage(), $ex->getCode() );
        }
        finally {
            $this->sendJsonResponse();
        }
    }
    
}
