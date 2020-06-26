<?php
define('APP_PATH', realpath('..'));

include_once APP_PATH . '/public/cronjobs/CronJobsConstans.php';
sleep(5);
$ch = curl_init( $local .'/api_devel/pin/afterconfirm');
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURLOPT_POSTFIELDS, "key=".$_SERVER['argv'][3]."&pinnumber=".$_SERVER['argv'][1]."&account_id=".$_SERVER['argv'][2] );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$respuesta = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);
echo $respuesta;
    			

