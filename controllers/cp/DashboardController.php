<?php

/**
 * Dashboard Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\Controller;
use Heliumframework\Auth;
use Heliumframework\Model\Device;

class DashboardController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        Auth::userLogged();
    }

    public function index()
    {
        $this->view('admin-panel.dashboard.main', [
            'apps' => [
                [
                    'name' => 'API Documentation',
                    'url' => 'api-documentation'
                ],
                [
                    'name' => 'Books',
                    'url' => 'books'
                ]
            ]
        ]);
    }
    
}
