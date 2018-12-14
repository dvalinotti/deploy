#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

<<<<<<< HEAD
function doLogin($username,$password)
=======

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
    $output = json_encode('success');
    echo 'End';
    return $output;
}

function doPush($destination, $version)
>>>>>>> cbe6e34dc6260f4c27774771c190c815ed88ee85
{
    // lookup username in databas
    // check password
    return true;
    //return false if not valid
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

