<?php
namespace Heliumframework\Model;

use Exception;
use Heliumframework\Model;

class Book extends Model {

    public function __construct($primaryKeyValue = '')
    {
        parent::__construct();
        $this->tablename    = 'tbl_books';
        $this->pk           = 'book_id';
        $this->pkValue      = $primaryKeyValue;
    }

    public function selectAll()
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl")->getResults();
    }

    public function selectPaginatedBooks( int $page = 1, $limit = 10 )
    {
        $pg = $page - 1;
        $tbl = $this->tablename;
        $rows = $this->rawQuery("SELECT * FROM $tbl LIMIT $pg, $limit")->getResults();
        $records = [
            'current_page' => $page,
            'total_records' => null, // to figure out
            'limit' => $limit
        ];
        $records['results'] = $rows['data'];
        return $records;
    }

    public function selectBookById( $bookId )
    {
        $tbl = $this->tablename;
        return $this->rawQuery("SELECT * FROM $tbl WHERE book_id = $bookId")->getRow();
    }

    public function removeBook()
    {
        $tbl = $this->tablename;
        $bookId = $this->pkValue;
        $this->rawQuery("DELETE FROM $tbl WHERE book_id = $bookId");
        return true;
    }

}