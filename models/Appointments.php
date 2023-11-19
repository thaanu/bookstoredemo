<?php
namespace Heliumframework\Model;

use Heliumframework\Model;
use \Exception;

class Appointments extends Model {

    public function __construct($primaryKeyValue = '')
    {
        parent::__construct();
        $this->tablename    = 'tbl_appointments';
        $this->pk           = 'appt_id';
        $this->pkValue      = $primaryKeyValue;
    }

    /**
     * Make a new appointment
     * 
     * @return boolean
     */
    public function newAppointment( $slotNumber, $prn, $nid, $mcr, $loggedInUsername, $currentDateTime )
    {
        $this->setPayload('slot_number', $slotNumber);
        $this->setPayload('prn', $prn);
        $this->setPayload('nid', $nid);
        $this->setPayload('appt_type', 'OPD');
        $this->setPayload('is_walkin', 0);
        $this->setPayload('doctor_mcr', $mcr);
        $this->setPayload('appt_created_by', $loggedInUsername);
        $this->setPayload('appt_created_dt', $currentDateTime);
        // Initial means the appointment has been initialized in local database, however an appointment is not created in Vesalius
        $this->setPayload('appt_status', 'INITIAL');

        return $this->store();
    }

    public function changeAppointment( $parentApptId, $deposit = [], $slotNumber, $prn, $nid, $mcr, $loggedInUsername, $currentDateTime )
    {
        $this->setPayload('slot_number', $slotNumber);
        $this->setPayload('prn', $prn);
        $this->setPayload('nid', $nid);
        $this->setPayload('appt_type', 'OPD');
        $this->setPayload('is_walkin', 0);
        $this->setPayload('doctor_mcr', $mcr);
        $this->setPayload('appt_created_by', $loggedInUsername);
        $this->setPayload('appt_created_dt', $currentDateTime);
        $this->setPayload('parent_appt_id', $parentApptId);
        // Initial means the appointment has been initialized in local database, however an appointment is not created in Vesalius
        $this->setPayload('appt_status', 'INITIAL');
        if ( ! $this->store() ) { return false; }
        // Update the old appointment status to changed
        $updateAppt = new Appointments($parentApptId);
        $updateAppt->setPayload('appt_status', 'CHANGED_DATE');

        if ( $deposit['status'] ) {
            $updateAppt->setPayload('appt_deposit_col', '1');
            $updateAppt->setPayload('appt_deposit_amount', $deposit['amount']);
        }

        $updateAppt->update();
        return true;
    }

    /**
     * Confirm the appointment
     * 
     * @return boolean
     */
    public function confirmAppointment( $appointmentNumber, $appointmentDatetime, $tokenNumber, $roomNumber )
    {
        if ( empty($this->pkValue) ) { throw new Exception('Appointment not selected'); }

        $this->setPayload('appt_number', $appointmentNumber);
        $this->setPayload('appt_dt', $appointmentDatetime);
        $this->setPayload('token_number', $tokenNumber);
        $this->setPayload('appt_room_no', $roomNumber);
        $this->setPayload('appt_status', 'CONFIRMED');
        return $this->update();
    }
    
    /**
     * Search Guest Appointments
     * 
     * @param string $query Can be either PRN or NID
     * @return array
     */
    public function selectGuestAppointments( $queryString, $page, $limit )
    {
        $tbl = $this->tablename;
        $currentPage = $page;
        $columns = ( empty($columns) ? '*' : implode(',', $columns) );
        $tbl = $this->tablename;
        $page = $page - 1;
        $pg = $page * $limit;
        $this->limitPerPage = $limit;

        // Constructing the query
        $sql  = "SELECT SQL_CALC_FOUND_ROWS a.*, d.doctor_name doctor_name FROM $tbl a";
        $sql .= " LEFT JOIN tbl_doctors d ON d.doctor_mcr = a.doctor_mcr ";
        $sql .= " WHERE a.prn = '$queryString' OR a.nid = '$queryString' ORDER BY a.appt_created_dt DESC LIMIT $pg, $limit ";

        $this->rawQuery($sql);
        $this->calculateTotalPages();
        
        return [
            'page'          => $currentPage,
            'limit'         => $limit,
            'total_pages'   => $this->totalPages,
            'total_records' => $this->totalRecords,
            'count'         => $this->result->num_rows,
            'data'          => $this->result->fetch_all(MYSQLI_ASSOC)
        ];

    }

    public function selectAppointment()
    {
        $tbl = $this->tablename;
        $apptId = $this->pkValue;
        return $this->rawQuery("SELECT * FROM $tbl WHERE appt_id = $apptId")->getResults();
    }

    public function cancelAppointment( $reason, $cancelledBy )
    {
        $this->setPayload('appt_cancelled_reason', $reason);
        $this->setPayload('appt_cancelled_by', $cancelledBy);
        $this->setPayload('appt_status', 'CANCELLED');
        
        return $this->update();
    }

    /**
     * Get the next token number
     * 
     * @return string
     */
    public function getNextTokenNumber( $roomNumber )
    {
        $tbl = $this->tablename;
        $currentDate = date('Y-m-d');
        $sql = "SELECT token_number FROM $tbl WHERE appt_room_no = '$roomNumber' AND date(appt_dt) = '$currentDate'";
        $records = $this->rawQuery($sql)->getResults();
        $tokenNumber = $records['count'] + 1;
        return str_pad($tokenNumber, 4, '0', STR_PAD_LEFT);
    }

    public function selectAll()
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl")->getResults();
    }

    public function collectDeposit( $amount )
    {
        $this->setPayload('appt_deposit_col', '1');
        $this->setPayload('appt_deposit_amount', $amount);
        return $this->update();
    }

}