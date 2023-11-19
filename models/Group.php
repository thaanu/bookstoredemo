<?php
namespace Heliumframework\Model;

use Exception;
use Heliumframework\Model;

class Group extends Model {

    public function __construct($primaryKeyValue = '')
    {
        parent::__construct();
        $this->tablename    = 'tbl_user_groups';
        $this->pk           = 'group_id';
        $this->pkValue      = $primaryKeyValue;
    }

    public function selectAll()
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl")->getResults();
    }

    public function selectGroupById()
    {
        $tbl = $this->tablename;
        $groupId = $this->pkValue;
        return $this->rawQuery("SELECT * FROM $tbl WHERE group_id = $groupId")->getRow();
    }

    public function selectRoles( int $groupId )
    {
        $roles = [];
        $tbl = $this->tablename;
        $records = $this->rawQuery("SELECT * FROM $tbl WHERE ug_id = $groupId")->getRow();
        if ( $records['count'] > 0 ) {
            $roles = explode(':', $records['data']['group_roles']);
        }
        return $roles;
    }

}