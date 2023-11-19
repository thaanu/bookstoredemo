<?php 

    // This application handles SMS notifications

    use Heliumframework\Model\Cronjobs;
    use Heliumframework\Logging;

    include dirname(__DIR__) . '/bootstrap.php';

    function init()
    {
        file_put_contents(____CHECKFILE,'1');
    }

    function incCount() 
    {
        $c = file_get_contents(____CHECKFILE);
        $c = $c + 1;
        file_put_contents(____CHECKFILE, $c);
    }

    function exceededTimeout()
    {
        $c = file_get_contents(____CHECKFILE);
        // if the count is greater than or equal to 5 (minutes)
        if ( $c >= 5 ) {
            return true;
        }
        return false;
    }

    function killme()
    {
        unlink(____CHECKFILE);
    }

    function sendNotification( $message ) 
    {
        shell_exec('curl -X POST -d "{\"text\": \"'.$message.'\"}" -H "Content-Type: application/json" https://api.pushcut.io/q5sFH41aBmrdVtXWHPCaa/notifications/Main%20queue%20-%20sms%20script');
    }

    define("____CHECKFILE", dirname(dirname(__DIR__ )). '/tmp/sms-script-running');

    
    // Terminate script if already running
    if ( file_exists(____CHECKFILE) ) {
        // Count number of retries, and if exceeded the cap notify Shan that there is a problem in the script
        if ( exceededTimeout() ) {
            killme();
            sendNotification('SMS Script is having an issue');
        } else {
            incCount();
        }
        exit;
    }

    // Initialize a file to flag that the application has started
    init();

    // Fetch all the pending SMS jobs
    $pending = (new Cronjobs())->selectPendingCronjobs();

    if ( $pending['count'] == 0 ) {
        killme();
        exit;
    }

    // Loop them and 
    foreach ( $pending['data'] as $job ) {

        $jobid = $job['cronjob_id'];
        $instructions = json_decode($job['cronjob_instructions'], true);

        if ( empty($instructions) ) {
            (new Cronjobs($jobid))->markAsFailed();
            continue; // Continue to next
        }

        $to = $instructions['to'];
        $message = $instructions['message'];

        $sms = sendSMS($to, $message);

        if ( ! $sms['status'] ) {
            (new Cronjobs($jobid))->markAsFailed();
            continue; // Continue to next
        }

        (new Cronjobs($jobid))->markAsCompleted();

    } 
    
    if (file_exists(____CHECKFILE)) { killme(); }
