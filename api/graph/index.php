<?php

require 'vendor/autoload.php';
require '../../graph_config.php';

use Aws\S3\S3Client;

$id = $_GET['accesskey'];
$graph = urlencode($_GET['reqkey']);
$ip = $_SERVER['REMOTE_ADDR'];
$fwd = $_SERVER['HTTP_X_FORWARDED_FOR'];
$error = 0;
$folder = 'rds2';

if (strspn($graph, "/\\'\"") > 0) $error = 1;

$conn = mysql_connect($my_host, $my_user, $my_password) or die('MySQL Error:' . mysql_error());
mysql_select_db($my_database) or die('MySQL Error: Could not select database');

$query = "SELECT user_id, level FROM `users` WHERE `key`='{$id}' LIMIT 1";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
$row = mysql_fetch_row($result);
$id = 0;
$level = 0;
if ($row) {
    $id = $row[0];
    $level = $row[1];
}

if ($id == 0)
    $error = 2;
   
if ($error == 0) {
    header('Access-Control-Allow-Origin: *');

    switch ($level) {
    case 1:
	// Create a client object
	$client = new S3Client([
	    'version' => 'latest',
	    'region'  => 'us-west-2',
	]);

	$client->registerStreamWrapper();

	if (isset($graph))
	readfile("s3://{$bucket}/{$folder}/{$graph}.json") or $error = 1;
	break;
	
    case 2:
	$demo = '2007-08-voyage-mineralogy-biota-433724';
	if ($graph == $demo) {
	    readfile("{$demo}.json") or $error = 1;
	} else
	    $error = 2;
	break;

    default:
	$error = 2;
    }
}

$key = addslashes($graph);
$log = "INSERT INTO logs SET user_id={$id}, `time`=NOW(), `key`='{$key}', ip='{$ip}', forwarded='{$fwd}', error={$error}";
mysql_query($log) or die('Query failed: ' . mysql_error());

switch ($error) {
    case 1:
	die('Invalid key');
    case 2:
	die('Access denied');
}

//readObject($client, 'rd-switchboard', "rda/".$_GET["graph"]);

/*
function readObject(S3Client $client, $bucket, $key)
{
    // Begin building the options for the HeadObject request
    $options = array('Bucket' => $bucket, 'Key' => $key);

    // Check if the client sent the If-None-Match header
    /* if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
         $options['IfNoneMatch'] = $_SERVER['HTTP_IF_NONE_MATCH'];
     }* /

    // Check if the client sent the If-Modified-Since header
    /* if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
	$options['IfModifiedSince'] = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
    }* /

    // Create the HeadObject command
    $command = $client->getCommand('HeadObject', $options);

    try {
	$response = $command->getResponse();
	    die("response");
    } catch (Aws\S3\Exception\S3Exception $e) {
        // Handle 404 responses
	die("error");

	http_response_code(404);
        exit;
    }
    
    die ("status");

    // Set the appropriate status code for the response (e.g., 200, 304)
    $statusCode = $response->getStatusCode();
    http_response_code($statusCode);
    
    // Let's carry some headers from the Amazon S3 object over to the web server
    $headers = $response->getHeaders();
    $proxyHeaders = array(
	'Last-Modified',
        'ETag',
        'Content-Type',
        'Content-Disposition'
    );

    foreach ($proxyHeaders as $header) {
	if ($headers[$header]) {
	header("{$header}: {$headers[$header]}");
        }
    }

    // Stop output buffering
    if (ob_get_level()) {
	ob_end_flush();
    }

    flush();
    // Only send the body if the file was not modified
    if ($statusCode == 200) {
	readfile("s3://{$bucket}/{$key}");
    }
}
*/

?>
