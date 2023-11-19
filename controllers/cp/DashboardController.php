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
        $this->view('admin-panel.dashboard.main');
    }

    public function fetchData()
    {
        try {
            $userId = loggedUser()->user_id;
            $this->formResponse = [
                'status' => 200,
                'view' => $this->returnView('admin-panel.dashboard._data', [
                    'devices' => (new Device())->selectUserDevicesLastRead($userId),
                    'device_types' => (new Device())->getDeviceTypes()
                ])
            ];
        } catch (Exception $ex) {
            $this->setError($ex->getMessage(), $ex->getCode());
        } finally {
            $this->sendJsonResponse();
        }
    }
}
