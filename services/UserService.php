<?php
namespace Heliumframework\Service;

use \Heliumframework\Model;

class UserService extends Model {

    private $error;

    public function emailExists( $email, $userId = null )
    {
        $unique = ($userId != null ? " AND user_id != $userId " : '');
        $this->rawQuery("SELECT user_id, email FROM tbl_users WHERE is_deleted = 0 AND email = '$email' $unique");
        $result = $this->getResults();
        if ( $result['count'] > 0 ) {
            $this->error = "$email already exists";
            return true;
        }
        return false;
    }

    public function contactNumberExists( $contactNumber, $userId = null )
    {
        $unique = ($userId != null ? " AND user_id != $userId " : '');
        $this->rawQuery("SELECT * FROM tbl_users WHERE is_deleted = 0 AND contact_no = '$contactNumber' $unique");
        $result = $this->getResults();
        if ( $result['count'] > 0 ) {
            $this->error = "$contactNumber already exists";
            return true;
        }
        return false;
    }

    public function hasAccess( $parentId, $userId )
    {
        $tbl = 'tbl_users';
        $results = $this->rawQuery("SELECT user_id FROM $tbl WHERE parent_id = $parentId AND user_id = $userId")->getResults();
        if ( $results['count'] > 0 ) {
            $this->error = "You do not have access to requested user";
            return true;
        }
        return false;
    }

    public function userDeleted( $userId )
    {
        $tbl = 'tbl_users';
        $results = $this->rawQuery("SELECT user_id FROM $tbl WHERE is_deleted = 1 AND user_id = $userId")->getResults();
        if ( $results['count'] > 0 ) {
            $this->error = "User is deleted";
            return true;
        }
        return false;
    }

    public function validOutletAccessLevel( $accessLevel )
    {
        $allowedAccessLevels = [3,4];
        if ( in_array($accessLevel, $allowedAccessLevels) ) {
            return true;
        }
        $this->error = "User is deleted";
        return false;
    }

    public function getError()
    {
        return $this->error;
    }


}