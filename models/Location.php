<?php
namespace Heliumframework\Model;

use Exception;
use Heliumframework\Model;

class Location extends Model {

    public function __construct($primaryKeyValue = '')
    {
        parent::__construct();
        $this->tablename    = 'tbl_locations';
        $this->pk           = 'location_id';
        $this->pkValue      = $primaryKeyValue;
    }

    public function selectAll()
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl")->getResults();
    }

    public function selectAllUserLocations( int $userId )
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl WHERE user_id = $userId ORDER BY location_name ASC")->getResults();
    }

    public function selectAllUserActiveLocations( int $userId )
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl WHERE user_id = $userId AND is_active = 1 ORDER BY location_name ASC")->getResults();
    }

    public function selectLocationById()
    {
        $locationId = $this->pkValue;
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl WHERE location_id = $locationId")->getRow();
    }

}