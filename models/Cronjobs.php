<?php
namespace Heliumframework\Model;

use Exception;
use Heliumframework\Model;

class Cronjobs extends Model {

    public function __construct($primaryKeyValue = '')
    {
        parent::__construct();
        $this->tablename    = 'tbl_cronjobs';
        $this->pk           = 'cronjob_id';
        $this->pkValue      = $primaryKeyValue;
    }

    public function selectAll()
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl ORDER BY cronjob_dt DESC")->getResults();
    }
    
    public function newSmsJob( $to, $message )
    {
        $instructions = json_encode([
            'to' => $to,
            'message' => $message
        ]);
        $this->setPayload('cronjob_instructions', $instructions);
        $this->setPayload('cronjob_dt', date('Y-m-d H:i:s'));
        $this->setPayload('cronjob_status', 'PENDING');
        $this->setPayload('cronjob_type', 'SMS');
        return $this->store();
    }

    public function newEmailJob( $to, $subject, $message, $template )
    {
        $instructions = json_encode([
            'to' => $to,
            'subject' => $subject,
            'template' => $template,
            'message' => $message
        ]);
        $this->setPayload('cronjob_instructions', $instructions);
        $this->setPayload('cronjob_dt', date('Y-m-d H:i:s'));
        $this->setPayload('cronjob_status', 'PENDING');
        $this->setPayload('cronjob_type', 'EMAIL');
        return $this->store();
    }

    public function selectPendingCronjobs() 
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl WHERE cronjob_status = 'PENDING'")->getResults();
    }

    public function markAsCompleted() 
    {
        if ( empty($this->pkValue) ) {
            throw new Exception('Job ID not defined', 400);
        }
        $this->setPayload('cronjob_status', 'COMPLETED');
        $this->setPayload('cronjob_run_dt', date('Y-m-d H:i:s'));
        return $this->update();
    }

    public function markAsFailed() 
    {
        if ( empty($this->pkValue) ) {
            throw new Exception('Job ID not defined', 400);
        }
        $this->setPayload('cronjob_status', 'FAILED');
        $this->setPayload('cronjob_run_dt', date('Y-m-d H:i:s'));
        return $this->update();
    }

    public function setupSms( $map )
    {
        $tbl = 'tbl_canned_text';
        $template = $this->rawQuery("SELECT * FROM $tbl WHERE canned_text_type = 'sms' AND canned_text_active = 1")->getResults();
        if ( $template['count'] == 0 ) {
            throw new Exception('Unable to find template', 404);
        }
        $template = $template['data'][0];
        $message = $template['canned_text'];
        foreach ( $map as $key => $value ) {
            $message = str_replace('{'.$key.'}', $value, $message);
        }
        return $message;
    }

}