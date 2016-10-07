<?php

include '../vendor/autoload.php';

use Att\M2X\MQTT\MQTTClient;

$apiKey = getenv("API_KEY");
$deviceId  = getenv("DEVICE");

$hostname = 'api-m2x.att.com';

try {
  $m2x = new MQTTClient($apiKey, array(
    'clientId' => 'PHP Test Client',
    'host' => gethostbyname($hostname)
  ));

  $m2x->connect();
  echo "Connected to the broker\n\r";

  $device = $m2x->device($deviceId);

  $params = array(
    "timestamp" => '2016-10-06T07:13:47.870Z',
    "values" => array(
    "temperature" => '800',
    "humidity" => '200'
    )
  );

  $response = $device->postSingleValueToMultipleStreams($params);

  echo "Status code $response->statusCode";
  if ($response->statusCode == 202) {
    echo  "\n\rUpdate Single Value to multiplestreams is Successful.";
  } else {
    echo  "\n\rUpdate Single Value to multiplestreams is Failed. Please Try Again.";
  }
  $m2x->disconnect();
} catch (Exception $ex) {
  echo sprintf('Exception Error: %s', $ex->getMessage());
  throw $ex;
}
