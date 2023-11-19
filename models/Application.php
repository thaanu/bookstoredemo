<?php
namespace Heliumframework\Model;

use Heliumframework\Model;

class Application extends Model {

    public function __construct($primaryKeyValue = '')
    {
        parent::__construct();
        $this->tablename    = 'tbl_api_clients';
        $this->pk           = 'client_id';
        $this->pkValue      = $primaryKeyValue;
    }

    public function selectAll()
    {
        $tablename = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tablename")->getResults();
    }

    public function selectApplicationByAuthKey( string $authKey )
    {
        $tablename = $this->tablename;
        $this->rawQuery("SELECT * FROM $tablename WHERE client_key = '$authKey'")->getRow();
    }

}