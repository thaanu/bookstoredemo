<?php 

    // This application handles SMS notifications

    use Heliumframework\Model\Cronjobs;
    use Heliumframework\Logging;

    include dirname(__DIR__) . '/bootstrap.php';

    $to = "9609991947";
    $message = "This is a test message from Main Queue";

    $sms = sendSMS($to, $message);

    var_dump($sms);