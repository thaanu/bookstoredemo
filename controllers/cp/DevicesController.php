<?php
/**
 * Devices Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\Controller;
use Heliumframework\Auth;
use Heliumframework\Model\Device;
use Heliumframework\Model\Location;

class DevicesController extends Controller {

    public function __construct()
    {
        parent::__construct();
        Auth::userLogged();
    }

    public function index()
    {
        $this->view('admin-panel.devices.main');
    }

    public function ajaxHandler( $action = '', $param = '' )
    {
        try {

            // Fetch all user devices
            if ( $action == 'fetch-user-devices' ) {

                $userId = loggedUser()->user_id;
                $this->formResponse['status'] = 200;
                $this->formResponse['view'] = $this->returnView('admin-panel.devices._data', [
                    'devices' => (new Device())->selectUserDevices($userId),
                    'device_types' => (new Device())->getDeviceTypes()
                ]);

            }
            // Show Add Device Form
            else if ( $action == 'add-device-form' ) {
                $this->formResponse['status'] = 200;
                $this->formResponse['view'] = $this->returnView('admin-panel.devices._create', [
                    'device_types' => $this->getDevicesToSelect(),
                    'locations' => $this->getLocations()
                ]);
            }
            // process Add Device
            else if ( $action == 'add-device' ) {

                $requiredFields = ['device_nickname', 'device_type'];

                foreach ( $requiredFields as $f ) {
                    if ( array_key_exists($f, $this->formData) && empty($this->formData[$f]) ) {
                        $f = ucwords(str_replace('_', ' ', $f));
                        throw new Exception($f . ' is required');
                    }
                }
                
                $userId = loggedUser()->user_id;
                $deviceUid = uniqid();
                $locationId = ( empty($this->formData['location_id']) ? null : $this->formData['location_id'] );
                $nickname = $this->formData['device_nickname'];
                $deviceType = $this->formData['device_type'];

                $device = new Device();
                $device->setPayload('device_nickname', $nickname);
                $device->setPayload('device_uid', $deviceUid);
                $device->setPayload('user_id', $userId);
                $device->setPayload('location_id', $locationId);
                $device->setPayload('device_status', 'off'); // todo: need to figure this
                $device->setPayload('device_type', $deviceType);

                if ( ! $device->store() ) {
                    throw new Exception('Unable to add device', 500);
                }

                $this->formResponse['status'] = 200;
                $this->formResponse['textMessage'] = 'Device added successfully';

            }
            // Edit device
            else if ( $action == 'show-device' ) {

                $userId = loggedUser()->user_id;
                $device = (new Device($param))->selectDeviceById();

                if ( $device['count'] == 0 ) { throw new Exception('Device not found', 404); }
                if ( $device['data']['user_id'] != $userId ) { throw new Exception('This device does not belong to you', 401); }

                $this->formResponse['status'] = 200;
                $this->formResponse['view'] = $this->returnView('admin-panel.devices._update', [
                    'device' => $device['data'],
                    'device_types' => $this->getDevicesToSelect(),
                    'locations' => $this->getLocations()
                ]);
            }
            // process update device
            else if ( $action == 'update-device' ) {

                $deviceId = $param;
                $userId = loggedUser()->user_id;
                $device = (new Device($deviceId))->selectDeviceById();

                if ( $device['count'] == 0 ) { throw new Exception('Device not found', 404); }
                if ( $device['data']['user_id'] != $userId ) { throw new Exception('This device does not belong to you', 401); }

                $requiredFields = ['device_nickname', 'device_type'];

                foreach ( $requiredFields as $f ) {
                    if ( array_key_exists($f, $this->formData) && empty($this->formData[$f]) ) {
                        $f = ucwords(str_replace('_', ' ', $f));
                        throw new Exception($f . ' is required');
                    }
                }
                
                $locationId = ( empty($this->formData['location_id']) ? null : $this->formData['location_id'] );
                $nickname = $this->formData['device_nickname'];
                $deviceType = $this->formData['device_type'];
                $deviceStatus = $this->formData['device_status'];

                $device = new Device( $deviceId );
                $device->setPayload('device_nickname', $nickname);
                $device->setPayload('location_id', $locationId);
                $device->setPayload('device_status', $deviceStatus);
                $device->setPayload('device_type', $deviceType);

                if ( ! $device->update() ) {
                    throw new Exception('Unable to update device', 500);
                }

                $this->formResponse['status'] = 200;
                $this->formResponse['textMessage'] = 'Device updated successfully';

            }
            // switch devices on or off
            else if ( $action == 'switch-device' ) {

                $deviceId = $param;
                $userId = loggedUser()->user_id;
                $device = (new Device($deviceId))->selectDeviceById();

                if ( $device['count'] == 0 ) { throw new Exception('Device not found', 404); }
                if ( $device['data']['user_id'] != $userId ) { throw new Exception('This device does not belong to you', 401); }

                $device = new Device( $deviceId );
                $device->setPayload('device_status', $this->formData['device_status']);

                if ( ! $device->update() ) {
                    throw new Exception('Unable to switch device', 500);
                }

                $this->formResponse['status'] = 200;
                $this->formResponse['textMessage'] = 'Device switched successfully';

            }
            // remove device
            else if ( $action == 'remove-device' ) {

                $deviceId = $param;
                $userId = loggedUser()->user_id;
                $device = (new Device($deviceId))->selectDeviceById();

                if ( $device['count'] == 0 ) { throw new Exception('Device not found', 404); }
                if ( $device['data']['user_id'] != $userId ) { throw new Exception('This device does not belong to you', 401); }

                $device = new Device( $deviceId );
                $device->setPayload('device_removed', 1);

                if ( ! $device->update() ) {
                    throw new Exception('Unable to remove device', 500);
                }

                $this->formResponse['status'] = 200;
                $this->formResponse['textMessage'] = 'Device removed successfully';

            }
            // Show reading sensor information
            else if ( $action == 'show-reading' ) {
                $deviceId = $param;
                $userId = loggedUser()->user_id;
                $device = new Device($deviceId);
                $theDevice = $device->selectDeviceById();

                if ( $theDevice['count'] == 0 ) { throw new Exception('Device not found', 404); }
                if ( $theDevice['data']['user_id'] != $userId ) { throw new Exception('This device does not belong to you', 401); }

                $this->formResponse = [
                    'status' => 200,
                    'view' => $this->returnView('admin-panel.devices._temperature', [
                        'data' => $device->getTemperature()
                    ])
                ];

            }
            else {
                throw new Exception('Invalid action', 404);
            }

        }
        catch ( Exception $ex ) {
            $this->setError( $ex->getMessage(), $ex->getCode() );
        }
        finally {
            $this->sendJsonResponse();
        }
    }
    
    private function getLocations()
    {
        $userId = loggedUser()->user_id;
        $locations = (new Location())->selectAllUserActiveLocations($userId);
        $loc = [];
        foreach ( $locations['data'] as $locat ) { $loc[$locat['location_id']] = $locat['location_name']; }
        return $loc;
    }

    private function getDevicesToSelect()
    {
        $dev = [];
        $devices = (new Device())->getDeviceTypes();
        if ( ! empty($devices) ) {
            foreach ( $devices as $k => $v ) { $dev[$k] = $v['label']; }
        }
        return $dev;
    }

}