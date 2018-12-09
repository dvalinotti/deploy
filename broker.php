#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('login.php.inc');
require_once('functions.inc');


function doBundle($source, $version)
{
    $result = false;
    foreach (ip as $target => $ip) {
        if (endsWith($target, $source)) {
            echo "BUNDLING:" .  $target . "\n";
            $b = bundle($source, str_replace('-' . $source, '', $target), $version);

            echo $b . "\n";
        }
    }

    return 'BUNDLE COMPLETE';
}

function doPush($destination, $version)
{
    foreach (ip as $target => $ip) {
        if (endsWith($target, $destination)) {
            $layer = strtok($target, '-');
            echo "Pushing ver" . $version . " to " . $layer . '-' . $destination . "...\n";
            $b = push($layer, $destination, $version);

            echo $b . "\n";
        }
    }
    return "PUSH COMPLETE";
}

function doRollback($source, $destination, $version)
{
    foreach (ip as $target => $ip) {
        if (endsWith($target, $destination)) {
            $layer = strtok($target, '-');
            echo "Rolling back " . $target . " to previous version...\n";
            $b = rollback($layer, $destination);

            echo $b . "\n";
        }
    }
    return "ROLLBACK COMPLETE";
}

function requestProcessor($request)
{
    echo "received request" . PHP_EOL;
    var_dump($request);
    
    
    if (!isset($request['type'])) {
        return "ERROR: unsupported message type";
    }
    switch ($request['type']) {
        case "bundle":
            return doBundle($request['source'], $request['version']);
        case "push":
            return doPush($request['destination'], $request['version']);
        case "rollback":
            return doRollback($request['layer']);
    }
    //log_message($request);
    return array(
        "returnCode" => '0',
        'message' => "Server received request and processed"
    );
}


$server = new rabbitMQServer("RabbitMQ.ini", "testServer");
echo "testRabbitMQServer BEGIN" . PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;
exit();
?>
