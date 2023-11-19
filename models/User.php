<?php
namespace Heliumframework\Model;

use Exception;
use Heliumframework\Model;

class User extends Model {

    public function __construct($primaryKeyValue = '')
    {
        parent::__construct();
        $this->tablename    = 'tbl_users';
        $this->pk           = 'user_id';
        $this->pkValue      = $primaryKeyValue;
    }

    public function selectAll()
    {
        $tbl = $this->tablename;
        $query = "SELECT u.*, g.group_name FROM $tbl u INNER JOIN tbl_user_groups g ON g.ug_id = u.user_group";
        return $this->rawQuery($query)->getResults();
    }

    public function selectUserById()
    {
        $tbl = $this->tablename;
        $userId = $this->pkValue;
        return $this->rawQuery("SELECT * FROM $tbl WHERE user_id = $userId")->getRow();
    }
    
    /**
     * Select a user by their email
     *
     * @param   string  $email  Email address
     *
     * @return  object          Return user information
     */
    public function selectUserByEmail( string $email )
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl WHERE email = '$email'")->getRow();
    }

    /**
     * Select all the users under a parent
     *
     * @param   int  $parentId  Unique ID given for the parent
     *
     * @return  array             Array of users
     */
    public function selectOutletUsers( $parentId )
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl WHERE parent_id = $parentId AND is_deleted = 0")->getResults();
    }

}