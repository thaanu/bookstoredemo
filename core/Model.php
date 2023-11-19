<?php
/**
 * MODEL : DATABASE
 * @author Ahmed Shan (@thaanu16)
 */
namespace Heliumframework;

use Exception;
use \Heliumframework\Logging;

class Model
{

    private $conn;
    protected $tablename, $payload = [], $pk, $pkValue;
    protected $result, $whereClause, $orderBy;
    private $error;
    private $last_record_id;
    protected $totalPages = 0, $totalRecords = 0, $limitPerPage = 10;

    public function __construct()
    {
        $this->conn = new \mysqli(_env('DB_HOST'), _env('DB_USER'), _env('DB_PASS'), _env('DB_NAME'));
        if ( ! $this->conn ) {
            throw new Exception('Database Connection Failed: ' . $this->conn->connect_error());
        }
    }

    // ! This method has bee depracated 
    // public function selectAll()
    // {
    //     $tbl = $this->tablename;
    //     $sortClause = $this->orderBy;

    //     $query = "SELECT * FROM $tbl $sortClause";
    //     $this->rawQuery($query);
    //     return [
    //         'count' => $this->result->num_rows,
    //         'data' => $this->result->fetch_all(MYSQLI_ASSOC)
    //     ];
    // }

    // ! This method has bee depracated 
    // public function setOrderBy( array $columns = [] )
    // {
    //     $this->orderBy = "ORDER BY ";
    //     foreach ( $columns as $item ) {
    //         $k = $item[0];
    //         $v = $item[1];
    //         $this->orderBy .= "$k $v,";
    //     }
    //     $this->orderBy = trim($this->orderBy, ',');
    // }

    public function where( $field, $value, $operation = '=' )
    {
        $value = ( is_string($value) ? "'".$value."'" : $value );
        $this->whereClause .= "$field $operation $value and ";
        return $this;
    }

    public function orderBy( $field, $direction )
    {
        $this->orderBy .= " $field $direction ";

        return $this;
    }

    public function select( $fields = [], $limit = 0)
    {
        $tbl = $this->tablename;

        $wc = '';
        if ( !empty($this->whereClause) ) {
            $wc = "WHERE " . $this->whereClause;
            $wc = rtrim($wc, "and ");
        }

        if ( ! empty($fields) ) {
            $fields = implode(',', $fields);
        } else {
            $fields = "*";
        }

        if ( $limit > 0  ) {
            $limit = " limit $limit";
        } else {
            $limit = "";
        }

        $query = "SELECT $fields FROM $tbl $wc $limit";

        $this->rawQuery($query);

        return $this;
    }

    public function setPayload( $field, $value )
    {
        $this->payload[$field] = $value;
        return $this;
    }

    public function paginate($page, $limit, $columns = [])
    {
        $currentPage = $page;
        $columns = ( empty($columns) ? '*' : implode(',', $columns) );
        $tbl = $this->tablename;
        $page = $page - 1;
        $pg = $page * $limit;
        $this->limitPerPage = $limit;
        $orderBy = '';

        if ( ! empty($this->orderBy) ) {
            $orderBy = " ORDER BY " . $this->orderBy;
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS $columns FROM $tbl $orderBy LIMIT $pg, $limit";
        $this->rawQuery($query);
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

    public function calculateTotalPages()
    {
        $result = $this->conn->query('SELECT FOUND_ROWS()');
        $this->totalRecords = $result->fetch_array()[0];
        $this->totalPages = ceil($this->totalRecords / $this->limitPerPage);
    }

    public function store()
    {
        // Check if payload is set
        if ( empty($this->payload) ) {
            throw new Exception('Payload is empty');
        }

        $tbl = $this->tablename;
        $keys = array_keys($this->payload);
        $values = array_values($this->payload);
        $cleanedValues = [];

        // Clean up values
        foreach ( $values as $value ) {
            if ( empty($value) ) {
                $cleanedValues[] = $value;
                continue;
            }
            $cleanedValues[] = str_replace("'", "\'", $value);
        }

        $columns = implode(",", $keys);
        $entries = "'" . implode("','", $cleanedValues) . "'";

        // Handle null
        $entries = str_replace("''", 'NULL', $entries);

        $query = "INSERT INTO $tbl ($columns) VALUES ($entries)";

        $this->rawQuery($query);

        if ( $this->result === TRUE ) {
            return true;
        }

        $this->error = $this->conn->error;

        (new Logging('mysql-error'))->error($this->error . "\n" . $query);

        return false;

    }

    public function update()
    {
        // Check if payload is set
        if ( empty($this->payload) ) {
            throw new Exception('Payload is empty');
        }

        // Check if PK is set
        if ( empty($this->pk) ) {
            throw new Exception('Primary key is required');
        }

        if ( empty($this->pkValue) ) {
            throw new Exception('Primary key value is required');
        }

        $pk = $this->pk;
        $pkv = $this->pkValue;
        $tbl = $this->tablename;
        $fields = '';

        foreach ( $this->payload as $k => $v ) {
            $v = str_replace("'", "\'", $v);
            $fields .= "$k = '$v',";
        }

        $fields = rtrim($fields, ',');
        $fields = str_replace("''", 'NULL', $fields);
        
        $query = "UPDATE $tbl SET $fields WHERE $pk = $pkv";

        $this->rawQuery($query);

        if ( $this->result === TRUE ) {
            return true;
        }

        $this->error = $this->conn->error;

        (new Logging('mysql-error'))->error($this->error . "\n" . $query);

        return false;
    }

    public function delete()
    {
        // Check if PK is set
        if ( empty($this->pk) ) {
            throw new Exception('Primary key is required');
        }

        if ( empty($this->pkValue) ) {
            throw new Exception('Primary key value is required');
        }

        $pk = $this->pk;
        $pkv = $this->pkValue;
        $tbl = $this->tablename;

        $query = "DELETE FROM $tbl WHERE $pk = $pkv";

        $this->rawQuery($query);

        if ( $this->result === TRUE ) {
            return true;
        }

        $this->error = $this->conn->error;

        (new Logging('mysql-error'))->error($this->error . "\n" . $query);

        return false;

    }

    public function rawQuery( $query )
    {
        $this->result = $this->conn->query($query);
        $this->last_record_id = $this->conn->insert_id;
        return $this;
    }

    public function getResults()
    {
        if ( gettype($this->result) == 'boolean' ) {
            return $this->result;
        } else {
            $results = $this->result->fetch_all(MYSQLI_ASSOC);
            $numRows = $this->result->num_rows;
            return [
                'count' => $numRows,
                'data' => $results
            ];
        }
    }

    public function getRow()
    {
        if ( gettype($this->result) == 'boolean' ) {
            return $this->result;
        } else {
            $results = $this->result->fetch_all(MYSQLI_ASSOC);
            $numRows = $this->result->num_rows;
            return [
                'count' => $numRows,
                'data' => ($numRows > 0 ? $results[0] : [])
            ];
        }
    }

    public function getError()
    {
        return $this->error;
    }

    public function getLastId()
    {
        $primaryKey = $this->pk;
        $tbl = $this->tablename;
        $result = $this->rawQuery("select MAX($primaryKey) last_id FROM $tbl")->getResults();
        return $result['data'][0]['last_id'];
    }

    public function __distruct() 
    {
        if ( $this->result ) {
            $this->result->free_result();
        }
        $this->conn->close();
    }

}