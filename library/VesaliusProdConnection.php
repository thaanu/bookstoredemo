<?php

namespace Vesalius;

use Heliumframework\Logging;
use \Exception;

class VesaliusProdConnection
{

	protected $conn;
	protected $results;
	protected $stid;

	public function __construct()
	{
		try {

			if ( (boolean) _env('VES_DEV_MODE') ) {
				$this->conn = oci_connect(_env('UAT_SCHEMA_USER'), _env('UAT_SCHEMA_PASS'), _env('UAT_SCHEMA_HOST') . '/' . _env('UAT_SCHEMA_SERV'));
			}
			else {
				$this->conn = oci_connect(_env('PROD_SCHEMA_USER'), _env('PROD_SCHEMA_PASS'), _env('PROD_SCHEMA_HOST') . '/' . _env('PROD_SCHEMA_SERV'));
			}

			if (!$this->conn) {
				throw new Exception("Prod Schema : Unable to connect");
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function getAllDoctors()
	{
		$query = $this->loadSQL('get-all-doctors');
		return $this->_executeQuery($query);
	}

	public function getDoctorSession( $mcr, $date = '' ) {
		if ( empty($date) ) {
			$date = strtoupper(date("d-M-y")); // Current Date
		} else {
			$date = strtoupper(date("d-M-y", strtotime($date)));
		}
		$query = $this->loadSQL('sessions');
		$query = str_replace('[::DATE::]', $date, $query);
		$query = str_replace('[::MCR::]', $mcr, $query);
		return $this->_executeQuery($query);
	}

	public function searchPatientByNIDorPRN( $searchQuery )
	{
		$query = $this->loadSQL('search-patient');
		$query = str_replace('[::SEARCH_QUERY::]', $searchQuery, $query);
		return $this->_executeQuery($query);
	}

	private function loadSQL( $filename ) {

		$queryDir = ( (boolean) _env('VES_DEV_MODE') ? 'vsuat' : 'vsprod' );

		$queryFile = dirname(__DIR__) . "/resources/vesalius-queries/$queryDir/$filename.sql";
		if ( ! file_exists($queryFile) ) {
			throw new Exception("$filename not found");
		}
		return file_get_contents($queryFile);
	}

	private function _executeQuery($query)
	{

		try {

			$stid = oci_parse($this->conn, $query);
			if (!oci_execute($stid)) {
				throw new Exception('Prod Schema: Unable to execute query');
			}

			// $results = oci_fetch_all($stid);
			oci_fetch_all($stid, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);

			if (oci_num_rows($stid) > 0) {
				return $results;
			}

			return [];
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
}
