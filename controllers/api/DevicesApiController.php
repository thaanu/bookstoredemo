<?php
/**
 * Devices Api Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\ApiController;
use Heliumframework\Model\Device;

class DevicesApiController extends ApiController {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {}

    /**
     * Normal Login
     * Guest will use their username and password to login
     *
     * @return  object  JSON object
     */
    public function postingData()
    {
        try {

            // Field validation
            $this->authField('uid');
            $this->authField('temperature');
            $this->authField('humidity');

            $uid = $this->post->uid;

            $device = (new Device())->selectDeviceByUID($uid);

            if ( $device['count'] == 0 ) {
                throw new Exception('Device not found', 404);
            }

            $deviceId = $device['data']['device_id'];
            $temp = $this->post->temperature;
            $humidity = $this->post->humidity;
            $cloudiness = NULL; // not using this value at the moment
            $precipitation = NULL; // not using this value at the moment
            $wind = NULL; // not using this value at the moment
            $atmp = NULL; // not using this value at the moment
            $datetime = date('Y-m-d H:i:s');

            $entryDone = (new Device())->setTemperature(
                $deviceId,
                $temp,
                $humidity,
                $cloudiness,
                $precipitation,
                $wind,
                $atmp,
                $datetime
            );

            if ( ! $entryDone ) {
                throw new Exception('Unable to record', 400);
            }
            
            $this->response['status'] = 200;
            $this->response['message'] = "Reading done";

        }
        catch ( Exception $ex ) {
            $this->setError($ex->getMessage(), $ex->getCode());
        }
        finally {
            $this->sendResponse();
        }
    }

    private function authField( $field )
    {
        if ( ! property_exists($this->post, $field) ) {
            throw new Exception("$field is required", 400);
        }
    }

}