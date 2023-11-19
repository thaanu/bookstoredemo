<?php
namespace Heliumframework\Model;

use Exception;
use Heliumframework\Model;

class Role extends Model {

    protected $results;

    public function __construct($primaryKeyValue = '')
    {
        parent::__construct();
        $this->tablename    = 'tbl_roles';
        $this->pk           = 'role_id';
        $this->pkValue      = $primaryKeyValue;
    }

    public function selectAll()
    {
        $tbl = $this->tablename;
        $this->results = $this->rawQuery("SELECT * FROM $tbl")->getResults();
        return $this->results;
    }

    public function selectRoles( int $groupId )
    {
        $roles = [];
        $tbl = $this->tablename;
        $records = $this->rawQuery("SELECT * FROM $tbl WHERE ug_id = $groupId")->getRow();
        if ( $records['count'] > 0 ) {
            $roles = explode(':', $records['data']['access_codes']);
        }
        return $roles;
    }

    public function shorten()
    {
        $roles = [];
        if ( $this->results['count'] > 0 ) {
            foreach ( $this->results['data'] as $role ) {
                $roles[] = $role['role_code'];
            }
        }
        return $roles;
    }

}