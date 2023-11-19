<?php
namespace Heliumframework\Model;

use Heliumframework\Model;
use \Exception;

class Audit extends Model {

    private $action, $who;

    public function __construct($primaryKeyValue = '')
    {
        parent::__construct();
        $this->tablename    = 'tbl_audits';
        $this->pk           = 'audit_id';
        $this->pkValue      = $primaryKeyValue;
    }

    public function getLogs( int $page = 1, int $limit = 1000)
    {
        $columns = [
            ['audit_dt', 'DESC']
        ];
        $this->setOrderBy($columns);
        return $this->paginate($page, $limit);
    }

    // public function makeSentence( string $who, string $action, string $where, string $why = '' )
    // {
    //     $this->who = $who;
    //     $this->action = "$who $action from $where." . (empty($why) ? '' : "Because $why");
    //     return $this;
    // }

    // public function set( string $route, string $eventType = 'Success' )
    // {

    //     if ( $eventType != 'Success' && $eventType != 'Failure' ) {
    //         throw new Exception('Invalid event type');
    //     }

    //     if ( empty($this->action) ) {
    //         throw new Exception('Action is required');
    //     }

    //     $actedBy = NULL;
    //     if ( !empty($this->who) ) {
    //         $actedBy = $this->who;
    //     }

    //     $this->setPayload('audit_dt', date('Y-m-d H:i:s'));
    //     $this->setPayload('route', $route);
    //     $this->setPayload('acted_by', $actedBy);
    //     $this->setPayload('event_type', $eventType);
    //     $this->setPayload('action', $this->action);
    //     if ( ! $this->store() ) {
    //         throw new Exception('Unable to set audit');
    //     }
    //     return true;
    // }

    /**
     * Set a audit record
     *
     * @param   string  $username     Username who is acting. Set as NULL to use the currently logged in user's username
     * @param   string  $actionLevel  Action Level, whether the audit from system, cronjob or user level
     * @param   string  $eventStatus  Event Status, whether success or failure
     * @param   string  $sysModule    Which module the audit is from
     * @param   string  $action       Audit message
     * @param   string  $data         Extra information such as CUrd
     *
     * @return  mixed                Return true on success | throw exception
     */
    static public function setAudit( $username, $actionLevel, $eventStatus, $sysModule, $action, $data = '')
    {

        $allowedActionLevels = ['User', 'System', 'Cronjob', 'API'];
        if ( !in_array($actionLevel, $allowedActionLevels) ) {
            throw new Exception('Invalid action level');
        }

        $allowedeventStatus = ['Success', 'Failure'];
        if ( !in_array($eventStatus, $allowedeventStatus) ) {
            throw new Exception('Invalid event status');
        }

        if ( $username == NULL ) {
            if ( \Heliumframework\Auth::isLoggedIn() ) {
                $username = \Heliumframework\Session::get('user')['email'];
            }
        }

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $route = json_encode([
            'uri' => $requestUri,
            'method' => $method
        ]);

        $itself = (new Audit());
        $itself->setPayload('audit_dt', date('Y-m-d H:i:s'));
        $itself->setPayload('username', $username);
        $itself->setPayload('route', $route);
        $itself->setPayload('action_level', $actionLevel);
        $itself->setPayload('event_status', $eventStatus);
        $itself->setPayload('ip_address', $ipAddress);
        $itself->setPayload('sys_module', $sysModule);
        $itself->setPayload('action', $action);
        $itself->setPayload('data', $data);

        if ( ! $itself->store() ) {
            throw new Exception('Unable to set audit');
        }
        return true;

    }

}