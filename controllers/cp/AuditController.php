<?php
/**
 * Audit Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\Controller;
use Heliumframework\Auth;
use Heliumframework\Model\Audit;

class AuditController extends Controller {

    public function __construct()
    {
        parent::__construct();
        Auth::userLogged();
    }

    public function index()
    {
        $this->view('admin-panel.audit.main');
    }

    public function fetch( $page = 1, $limit = 30 )
    {
        $this->view('admin-panel.audit._data', [
            'auditLogs' => (new Audit())->getLogs($page, $limit)
        ]);
    }

}