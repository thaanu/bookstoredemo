<?php
namespace Heliumframework\Model;

use Heliumframework\Model;

class Application extends Model {

    public function __construct($primaryKeyValue = '')
    {
        parent::__construct();
        $this->tablename    = 'tbl_api_apps';
        $this->pk           = 'app_id';
        $this->pkValue      = $primaryKeyValue;
    }

    public function selectApplicationByAuthKey( string $authKey )
    {
        $tablename = $this->tablename;
        $this->rawQuery("SELECT * FROM $tablename WHERE app_auth_key = '$authKey'");
        return $this->getOne();
    }

}