<?php

/**
 * EXTRAS
 * @author Ahmed Shan (@thaanu16)
 * 
 * Extra helper functions
 */

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use \Heliumframework\Logging;

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source https://gravatar.com/site/implement/images/php/
 */
function getGravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
{
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5(strtolower(trim($email)));
    $url .= "?s=$s&d=$d&r=$r";
    if ($img) {
        $url = '<img src="' . $url . '"';
        foreach ($atts as $key => $val)
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

/**
 * Create a csv file
 * @param array $dataset
 * @param string $destinationPath
 * @return boolean
 */
function createCSV($dataset, $destinationPath)
{
    $fp = fopen($destinationPath, 'w');

    foreach ($dataset as $fields) {
        fputcsv($fp, $fields);
    }

    fclose($fp);
}

/**
 * Return currently logged in user information
 */
function loggedUser()
{
    if (\Heliumframework\Session::exists('user')) {
        return json_decode(json_encode(\Heliumframework\Session::get('user')));
    }
}

/**
 * Calculate Age
 * @param int $dob
 * 
 * @return int
 */
function ageCal($birthDate)
{

    //explode the date to get month, day and year
    $birthDate = explode("-", $birthDate);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
        ? ((date("Y") - $birthDate[0]) - 1)
        : (date("Y") - $birthDate[0]));

    return ($age >= 0 ? $age : false);
}

/**
 * Add minutes to date time
 *
 * @param   string  $date  Date
 * @param   int     $minutes  Minutes to add
 * @param   string  $format  Date time format to return
 *
 * @return  string
 */
function addMinutesToDate($date, $minutes, $format = 'Y-m-d H:i:s')
{
    return date('Y-m-d H:i:s', strtotime("+$minutes minute", strtotime($date)));
}

/**
 * Send an sms using Dhiraagu SMS Gateway
 *
 * @param   string  $to       Mobile Number formated as 9607700000
 * @param   string  $message  Message to send
 *
 * @return  void            True for success and message for failed
 */
function sendSMS($to, $message)
{
    $status = [
        'status' => false,
        'message' => ''
    ];
    try {
        $smsClient = new \Dash8x\DhiraaguSms\DhiraaguSms(_env('DHIRAAGU_SMS_USERNAME'), _env('DHIRAAGU_SMS_PASSWORD'));
        $smsClient->send("+$to", $message);
        $status = [
            'status' => true,
            'message' => 'SMS Sent'
        ];
    }
    catch( \Dash8x\DhiraaguSms\Exception\DhiraaguSmsException $e ) {
        echo "Dhiraagu SMS Exception\n";
        $status['message'] = $e->getMessage();
    }
    catch( Exception $e ) {
        $status['message'] = "error message";
    }
    finally {
        return $status;
    }
}

/**
 * Format an sms text message
 *
 * @param   string $template
 * @param   array  $dataset  Text replacement dataset
 *
 * @return  string            
 */
function formatSMS($template, $dataset)
{
    $sms_text = $template;
    foreach ($dataset as $k => $v) {
        $sms_text = str_replace($k, $v, $sms_text);
    }
    return $sms_text;
}


/**
 * Send an email
 *
 * @param   array   $to        Should Include ['email' => '', 'name' => '']
 * @param   array   $from     Should Include ['email' => '', 'name' => '']
 * @param   string  $subject  Email Headers
 * @param   string  $body     HTML Body Message
 * @param   string  $headers  Email Headers
 * @param   array   $attachments  Array of files to attach
 *
 * @return  string
 */
function sendEmail($to, $from, $subject, $body, $headers = null, $attachments = [])
{

    // Send Email
    try {

        // Initialize PHP Mailer
        $mail = new PHPMailer(true);

        //Server settings
        $mail->SMTPDebug = 0;                                           // Enable verbose debug output
        $mail->isSMTP();                                                // Set mailer to use SMTP
        $mail->Host       = _env('EMAIL_SMTP_SERVER');          // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = _env('EMAIL_SMTP_USER');            // SMTP username
        $mail->Password   = _env('EMAIL_SMTP_PASS');            // SMTP password
        $mail->SMTPSecure = _env('EMAIL_SMTP_MODE');
        $mail->Port       = _env('EMAIL_SMTP_PORT');

        //Recipients
        $mail->setFrom($from['email'], $from['name']);
        $mail->addAddress($to['email'], $to['name']);           // Add a recipient

        // Content
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = $subject;

        $mail->Body    = $body;

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        // Attach files
        if (!empty($attachments)) {
            foreach ($attachments as $file) {
                $mail->addAttachment($file);
            }
        }


        $mail->send();

        return true;
    } catch (Exception $e) {

        (new Logging('email-script'))->debug($mail->ErrorInfo);

        return false;
    }
}

/**
 * Sending Internal Mail Using HELP DESK
 *
 * @param   array   $to        Should Include ['email' => '', 'name' => '']
 * @param   string  $subject  Email Headers
 * @param   string  $body     HTML Body Message
 * @param   string  $headers  Email Headers
 * @param   array   $attachments  Array of files to attach
 *
 * @return  string
 */
function sendInternalEmail($to, $subject, $body, $headers = null, $attachments = [])
{

    // Send Email
    try {

        // Initialize PHP Mailer
        $mail = new PHPMailer(true);

        //Server settings
        $mail->SMTPDebug = 0;                                           // Enable verbose debug output
        $mail->isSMTP();                                                // Set mailer to use SMTP
        $mail->Host       = _env('INTERNAL_EMAIL_SMTP_SERVER');          // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = _env('INTERNAL_EMAIL_SMTP_USER');            // SMTP username
        $mail->Password   = _env('INTERNAL_EMAIL_SMTP_PASS');            // SMTP password
        $mail->SMTPSecure = _env('INTERNAL_EMAIL_SMTP_MODE');
        $mail->Port       = _env('INTERNAL_EMAIL_SMTP_PORT');

        //Recipients
        $mail->setFrom(_env('INTERNAL_EMAIL_ADDRESS'), _env('INTERNAL_EMAIL_NAME'));
        $mail->addAddress($to['email'], $to['name']);           // Add a recipient

        // Content
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = $subject;

        $mail->Body    = $body;

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        // Attach files
        if (!empty($attachments)) {
            foreach ($attachments as $file) {
                $mail->addAttachment($file);
            }
        }


        $mail->send();

        return true;
    } catch (Exception $e) {

        log_message(print_r($mail->ErrorInfo, true));
        return false;
    }
}

/**
 * Format an email
 *
 * @param   string  $template  HTML Template
 * @param   array  $dataset   Dataset to replace keywords
 *
 * @return  string             
 */
function formatEmail($template, $dataset)
{
    foreach ($dataset as $k => $v) {
        $template = str_replace($k, $v, $template);
    }
    return $template;
}


/**
 * Get File MIME type
 *           
 */
function getFileMimeType($file) {
    if (function_exists('finfo_file')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $file);
        finfo_close($finfo);
    } else {
        require_once 'upgradephp/ext/mime.php';
        $type = mime_content_type($file);
    }

    if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
        $secondOpinion = exec('file -b --mime-type ' . escapeshellarg($file), $foo, $returnCode);
        if ($returnCode === 0 && $secondOpinion) {
            $type = $secondOpinion;
        }
    }

    if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
        require_once 'upgradephp/ext/mime.php';
        $exifImageType = exif_imagetype($file);
        if ($exifImageType !== false) {
            $type = image_type_to_mime_type($exifImageType);
        }
    }

    return $type;
}

/**
 * Check file signature
 * @param string $test
 * @param string $sig
 */


function testsig($test, $sig)
{
    // remove spaces in sig
    $sig = str_replace(" ", "", $sig);
    if (substr($test, 0, strlen($sig)) == $sig) {
        return true;
    }
    return false;
}

/**
 * Check string to hex
 * @param string $string
 */
function strToHex($string, $stop = null)
{
    $hex = "";
    if ($stop == null) {
        $stop = strlen($string);
    }
    $stop = min(strlen($string), $stop);

    for ($i = 0; $i < $stop; $i++) {
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0' . $hexCode, -2);
    }
    return strtoupper($hex);
}

/**
 * Check file type
 * @param string $test
 */
function getfiletype($test)
{
    if (testsig($test, "FF D8 FF")) {
        return "jpeg";
    }
    if (testsig($test, "4D 5A")) {
        return "exe";
    } elseif (testsig($test, "25 50 44 46")) {
        return "pdf";
    } elseif (testsig($test, "D0 CF 11 E0 A1 B1 1A E1")) {
        return "doc";
    } elseif (testsig($test, "89 50 4E 47 0D 0A 1A 0A")) {
        return "png";
    } elseif (testsig($test, "FD FF FF FF")) {
        return "xls";
    } elseif (testsig($test, "50 4B 03 04" || "50 4B 03 04 14 00 06 00")) {
        return "xlsx";
    } else {
        return "unknown";
    }
}
