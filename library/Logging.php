<?php
namespace Heliumframework;
use Exception;

class Logging {

    private $logDir;
    private $logFilename;

    /**
     * Initialize logging
     *
     * @param   string  $filename  Optional log filename
     *
     * @return  void             Does not return anything
     */
    public function __construct( $filename = null )
    {
        $this->logDir = dirname(__DIR__) . '/logs';

        // Create log folder if does not exists
        if ( ! is_dir($this->logDir) ) {
            mkdir($this->logDir);
        }

        $this->logFilename = $this->logDir . '/default_system.log';

        // Create a specific file for logging
        if ( $filename != null ) {
            $this->setFilename($filename);
        }

    }

    /**
     * Set a specific log filename
     *
     * @param   string  $filename  Log filename
     *
     * @return  mixed             Return self on success or throw exception
     */
    public function setFilename( $filename = null )
    {
        try {
            if ( $filename == null ) {
                throw new Exception('Please enter log filename');
            }
            if ( strpos($filename, '/') !== false ) {
                throw new Exception('Invalid filename');
            }
            $this->logFilename = $this->logDir . "/$filename.log";
            return $this;
        }
        catch ( Exception $e ) {
            die($e->getMessage());
        }
    }

    public function debug( $message )
    {
        return $this->log( $message, "DEBUG" );
    }

    public function info( $message )
    {
        return $this->log( $message, "INFO" );
    }

    public function warning( $message )
    {
        return $this->log( $message, "WARNING" );
    }

    public function error( $message )
    {
        return $this->log( $message, "ERROR" );
    }

    public function log( $message, $messageType )
    {
        try {
            // Check if file exists
            if ( ! file_exists($this->logFilename) ) {
                touch($this->logFilename);
            }

            // Handle arrays
            if ( is_array($message) ) {
                $message = print_r($message, true);
            }

            // Handle objects
            if ( is_object($message) ) {
                $message = print_r($message, true);
            }

            $messageType = strtoupper($messageType);

            $datetime = date('Y-m-d H:i:s');
            $oldContent = file_get_contents($this->logFilename);
            $newContent =  "$datetime\n$messageType\t$message\n\n$oldContent";
            file_put_contents($this->logFilename, $newContent);
            return $this;
        }
        catch ( Exception $e ) {
            die($e->getMessage());
        }
    }

    public function readLog( $logFile = null )
    {

        $colors = [
            'DEBUG' => 'orange',
            'INFO' => '#669a00',
            'WARNING' => '#e4b40d',
            'ERROR' => 'red'
        ];

        if ( $logFile == null ) {
            $logFile = $this->logFilename;
        }
        $content = file_get_contents($logFile);
        $content = explode("\n", $content);
        $output = '';
        extract($colors);
        foreach ( $content as $line ) {

            $l = $line;
            $l = str_replace('DEBUG', "<span style='color: $DEBUG;'>DEBUG</span>", $l);
            $l = str_replace('INFO', "<span style='color: $INFO;'>DEBUG</span>", $l);
            $l = str_replace('WARNING', "<span style='color: $WARNING;'>DEBUG</span>", $l);
            $l = str_replace('ERROR', "<span style='color: $ERROR;'>DEBUG</span>", $l);

            $output .= "$l<br>";

        }

        return $output;

    }

}