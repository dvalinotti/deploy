#!/usr/bin/php
<?php
include('Net/SSH2.php');
include('Crypt/RSA.php');
require_once('rabbitMQLib.inc');

const ip = [
    'frontend-qa'   => '10.0.142.134',
    'api-qa'        => '10.0.136.105',
    'db-qa'         => '10.0.20.102',
    'frontend-live' => '10.0.142.19',
    'api-live'      => '10.0.187.245',
    'db-live'       => '10.0.214.181'
];

function log_api_data($rid, $rUser, $accountCreationDate, $verified)
{
    $db = mysqli_connect('localhost', 'emile', 'Password7!', 'authtest');

    $rUser = mysqli_real_escape_string($db, $rUser);
    $s = "SELECT * FROM reddit_data WHERE redditorID ='". $rid ."';";
    echo $s;
    ($t = mysqli_query($db, $s) or die(mysqli_errno($db)));
    $num = mysqli_num_rows($t);
    $result = false;
    echo $num;
    if ($num == 1) {
        echo "already exists";
        $result = true;
    } else {
        $s = "INSERT INTO reddit_data VALUES('".$rid."', '".$rUser."', CAST('". $accountCreationDate ."' AS DATE), '".$verified."');";
        $t = (mysqli_query($db, $s));

        echo $result;
    }
    //echo $result;
    return $result;
}

function bundle($layer, $target, $version)
{
    $host = $target . '-' . $layer;
    $ssh = new Net_SSH2(ip[$host]);
    $key = new Crypt_RSA();
    $key->loadKey(file_get_contents('keys/'.$host . '.pem'));
    echo 'attempting connection';
    if (!$ssh->login('ubuntu', $key)) {
        exit('Login Failed');
    }
    
    $ssh->setTimeout(6);
    $read = 'ubuntu@ip-' . str_replace('.','-', ip[$host]) . ':~$';
    echo $ssh->read($read);
    $ssh->write("tar cvzf ". $version . ".tar.gz git/" . $target . "/\n");
    echo $ssh->read($read);
    $ssh->write("scp -i deploy.pem " . $version . '.tar.gz ubuntu@10.0.239.193:"/home/ubuntu/deploy/bundles/' . $target . '/"' . "\n");
    echo $ssh->read($read);
    $ssh->write("rm -rf " . $version . ".tar.gz\n");
    $result = $ssh->read();
    echo $result . "\n";
    unset($ssh);
    return "Success";
}

function push($source, $version, $destination) {
    $bundle = $version . '.tar.gz';
    $read = 'ubuntu@ip-' . str_replace('.','-', ip[$host]) . ':~$';

    if (! file_exists("/bundles/" . $bundle)) {
        return 'ERROR: Bundle "' . $bundle . '" does not exist.';
    }

    $ssh = new Net_SSH2(ip[$host]);
    $key = new Crypt_RSA();
    $key->loadKey(file_get_contents('keys/'.$host . '.pem'));
    echo 'attempting connection';
    if (!$ssh->login('ubuntu', $key)) {
        exit('Login Failed');
    }
    $ssh->setTimeout(6);
    echo $ssh->read($read);
    $ssh-> write();
}

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}
?>
