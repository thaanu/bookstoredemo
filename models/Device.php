<?php
namespace Heliumframework\Model;

use Exception;
use Heliumframework\Model;

class Device extends Model {

    protected $deviceTypes = [
        'light' => [
            'label' => 'Light',
            'icon' => 'fas fa-lightbulb'
        ],
        'temp-sensor' => [
            'label' => 'Temperature Sensor',
            'icon' => 'fas fa-temperature-high'
        ]
    ];

    public function __construct($primaryKeyValue = '')
    {
        parent::__construct();
        $this->tablename    = 'tbl_devices';
        $this->pk           = 'device_id';
        $this->pkValue      = $primaryKeyValue;
    }

    public function selectAll()
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl")->getResults();
    }

    public function selectUserDevices( int $userId )
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl WHERE user_id = $userId AND device_removed = 0")->getResults();
    }

    public function selectDeviceById()
    {
        $deviceId = $this->pkValue;
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl WHERE device_id = $deviceId")->getRow();
    }

    public function selectDeviceByUID( $uid )
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl WHERE device_uid = '$uid'")->getRow();
    }

    public function getDeviceTypes()
    {
        return $this->deviceTypes;
    }

    public function setTemperature( $deviceId, $temp, $humidity, $cloudiness, $precipitation, $wind, $atmp, $datetime )
    {
        $this->tablename = 'tbl_temperature';
        $this->setPayload('device_id', $deviceId);
        $this->setPayload('temp_v', $temp);
        $this->setPayload('humidity', $humidity);
        $this->setPayload('cloudiness', $cloudiness);
        $this->setPayload('precipitation', $precipitation);
        $this->setPayload('wind', $wind);
        $this->setPayload('atmp', $atmp);
        $this->setPayload('entry_dt', $datetime);
        return $this->store();
    }

    public function getTemperature()
    {
        $tbl = 'tbl_temperature';
        $deviceId = $this->pkValue;
        return $this->rawQuery("SELECT * FROM $tbl WHERE device_id = $deviceId ORDER BY entry_dt DESC")->getResults();
    }

    public function selectUserDevicesLastRead( $userId )
    {
        $query = "
        select a.*, l.location_name, 
        (select temp_v from tbl_temperature where device_id = a.device_id order by entry_dt desc limit 1) temp, 
        (select humidity from tbl_temperature where device_id = a.device_id order by entry_dt desc limit 1) humidity, 
        (select wind from tbl_temperature where device_id = a.device_id order by entry_dt desc limit 1) wind,
        (select cloudiness from tbl_temperature where device_id = a.device_id order by entry_dt desc limit 1) cloudiness,
        (select atmp from tbl_temperature where device_id = a.device_id order by entry_dt desc limit 1) atmp,
        (select precipitation from tbl_temperature where device_id = a.device_id order by entry_dt desc limit 1) precipitation,
        (select entry_dt from tbl_temperature where device_id = a.device_id order by entry_dt desc limit 1) updated_dt
        from tbl_devices a 
        inner join tbl_locations l on l.location_id = a.location_id 
        where 
            a.user_id = $userId 
            and a.device_removed = 0
            and a.device_status = 'on'
        ";
        return $this->rawQuery($query)->getResults();
    }


}