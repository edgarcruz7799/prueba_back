<?php

defined('APP_PATH') || define('APP_PATH', realpath('..'));

// Read the configuration, auto-loader and services
$config = include APP_PATH . "/app/config/config.php";
include APP_PATH . "/app/config/loader.php";
include APP_PATH . "/app/config/services.php";


$queue = $di->getShared('queue');

while (($job = $queue->peekReady()) !== false) {
    $message = $job->getBody();

    $local ='http://localhost';
	$key = '9870b81a6068c7c5a58ceb08931aefa2537f278ca2d0264381236d67219a9130';
    $ch = curl_init($local.'/api_devel/scheduler/automaticprogramming');
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, "key=".$key);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$respuesta = curl_exec($ch);
	$error = curl_error($ch);
	curl_close($ch);
	echo $respuesta;

    $job->delete();
}
