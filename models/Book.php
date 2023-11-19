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