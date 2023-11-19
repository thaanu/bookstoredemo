<?php
/**
 * FILES CLASS
 * @author Ahmed Shan (@thaanu16)
 */

 namespace Heliumframework;

class Files 
{

    protected $file_arr;
    protected $file_destination;
    protected $_errors = [];
    protected $allowed_filesize = null;
    protected $allowed_filetypes = null;
    protected $final_file_path;
    public $final_filename;

    public function __construct($file_arr, $file_destination)
    {
        // Set the variables
        $this->file_arr = $file_arr;
        $this->file_destination = $file_destination;

        // Check whether the destination is available, if not create destination
        if( is_dir($file_destination) == false ) {
            // Create the file path recursively
            mkdir($file_destination, 0775, true);
        }
        
    }

    /**
     * Upload the file
     * @param string $newfilename
     * @return boolean
     */
    public function upload( $newfilename = null )
    {

        // A flag
        $all_good_to_go = true;

        // PHP File Upload Errors
        if( $this->file_arr['error'] == 0 ) {

            // Check if allowed file size is set, if so check if the file size is correct
            if( $this->allowed_filesize != null ) {
                if( $this->file_arr['size'] > $this->allowed_filesize ) {
                    $this->_errors[] = 'Filesize is greater than allowed file size';
                    $all_good_to_go = false;
                }
            }

            // Check if allowed file types are set, if so check if the file types are correct
            if( $this->allowed_filetypes != null && !empty($this->allowed_filetypes) ) {

                if( !in_array($this->get_extension(), $this->allowed_filetypes) ) {
                    $this->_errors[] = 'Invalid file type';
                    $all_good_to_go = false;
                }

            }


            // Check if everything is good to go
            if( $all_good_to_go ) {

                // Set the filename
                $filename = ( $newfilename == null ? $this->file_arr['name'] : $newfilename );

                $this->final_file_path = $this->file_destination . '/' . $filename;
        
                // Move the uploaded file
                if( move_uploaded_file($this->file_arr['tmp_name'], $this->final_file_path) ) {

                    $this->final_filename = $filename;
        
                    return true;
        
                } else {
        
                    // Set an error message
                    $this->_errors[] = 'Unable to move file';
                    
                }

            }
        
        } 
        // Else show error
        else {

            // Get the error message
            $this->_errors[] = $this->file_error_explained( $this->file_arr['error'] );

        }

        
        // By default return false
        return false;

    }

    /**
     * Set allowed filesize
     * @param int $filesize
     * @return boolean
     */
    public function set_allowed_filesize( $filesize )
    {
        $this->allowed_filesize = $filesize;
    }

    /**
     * Set allowed filesize
     * @param array $filetypes
     * @return boolean
     */
    public function set_allowed_filetypes( $filetypes )
    {
        $this->allowed_filetypes = $filetypes;
    }

    /**
     * Convert given file size to bytes
     * @param int $size
     * @param string $unit
     * @return int
     */
    private function _converter( $size, $unit )
    {

        // Todo: Need to get this converter working, so dont need to set in bytes each time
        // 1mb = 1000000 bytes

    }

    /**
     * Get file extension
     * @return string
     */
    public function get_extension()
    {
        return strtolower(pathinfo($this->file_arr['name'])['extension']);
    }

    /**
     * Get file size
     * @return string
     */
    public function get_size()
    {
        return $this->file_arr['size'];
    }

    /**
     * Get the final file path
     * @return string
     */
    public function get_file_path()
    {
        return $this->final_file_path;
    }

    /**
     * Get all file upload errors
     * @return array
     */
    public function errors()
    {
        return $this->_errors;
    }

    /**
     * File upload errors explained
     * @param int $error_code
     * @return string
     */
    public function file_error_explained( $error_code )
    {
        // todo: Create a list of file error messages
        return 'PHP FILE ERROR FOR NOW';
    }

}