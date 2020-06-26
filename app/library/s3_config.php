<?php

// Bucket Name
$bucket="logisticapp";

//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAITFCZOYERMEPJ3KQ');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');

//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);
$actual_image_name = "$provider->nombre/$folder/".time().$name;

if($s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) ) {

    $s3file='http://'.$bucket.'.s3.amazonaws.com/'.$actual_image_name;

}
else
    $s3file = "N/A";

?>