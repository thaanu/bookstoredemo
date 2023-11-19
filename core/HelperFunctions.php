<?php 

/**
 * Human readable file size
 *
 * @param   string  $size  Filesize in bytes
 * @param   string  $unit  Return Unit
 *
 * @return  string         Return file size
 */
function humanFileSize($size,$unit="") {
    if( (!$unit && $size >= 1<<30) || $unit == "GB")
        return number_format($size/(1<<30),2)."GB";
    if( (!$unit && $size >= 1<<20) || $unit == "MB")
        return number_format($size/(1<<20),2)."MB";
    if( (!$unit && $size >= 1<<10) || $unit == "KB")
        return number_format($size/(1<<10),2)."KB";
    return number_format($size)." bytes";
}

function logUserActivity( $username, $action, $url = '' )
{
    $today = date("Y-m-d");
    $message = "\nusername: $username\naction: $action\nurl:$url\n";
    $logFolder = dirname(__DIR__) . "/logs/user-activities";
    $logFile = "$logFolder/$today.log";
    if ( ! is_dir($logFolder) ) { mkdir($logFolder, 0775); }
    log_message( $message, $logFile );
}

/**
 * This function validates mobile phone number
 *
 * @param   string $mobileNumber
 * 
 * @return  boolean  Return true on valid | false
 */
function validateMobileNumber( string $mobileNumber )
{
    preg_match("/(\+\d{1,3}[- ]?)?\d{10}/", $mobileNumber, $mm);
    return ( empty($mm) ? false : true );
}

/**
 * Validate email
 *
 * @param   string  $email  Email Address
 *
 * @return  boolean          Return true on valid | false
 */
function validateEmailAddress( $email )
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

/**
 * This function returns the phone number by hidding some of the digits in the middle of the phone number
 *
 * @param   string  $phoneNumber  Phone Number, Mobile Number
 *
 * @return  string                Secret number
 */
function getSecretMobileNumber( string $phoneNumber )
{
    $onlyNumber = substr($phoneNumber, 3, strlen($phoneNumber));
    $fone = substr($onlyNumber, 0, 1);
    $ltwo = substr($onlyNumber, 3, strlen($phoneNumber));
    return "$fone..$ltwo";
}

function hfDateTimeFormat( $datetime )
{
    return date(_env('DT_FORMAT'), strtotime($datetime));
}

function hfDateFormat( $date )
{
    return date(_env('D_FORMAT'), strtotime($date));
}

function hfTimeFormat( $time )
{
    return date(_env('T_FORMAT'), strtotime($time));
}

function hfDashesToCamelCase($string, $capitalizeFirstCharacter = false) 
{
    $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    if (!$capitalizeFirstCharacter) {
        $str[0] = strtolower($str[0]);
    }
    return $str;
}

function cleanupBreadcrumb( $text ) 
{
    $text = str_replace('-', ' ', $text);
    $text = ucwords($text);
    return $text;
}