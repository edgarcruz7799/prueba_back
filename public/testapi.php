<?php
/**
 * Created by PhpStorm.
 * User: Diego Zarate
 * Date: 25/04/2016
 * Time: 11:41 AM
 */

/*$data ='{"key" : "1fc0f5604616c93deac481b33989f10e",
         "diligences": [{"longitude":"-74.051830284297","phone":"1479630","latitude":"4.6776307065399","address":"Calle 93","name":"Pepito"},
                         {"phone":"3045556998","longitude":"-74.05119258910418","latitude":"4.675341376591047","address":"Avenida Alejandro Obregón;Calle 92","name":"juan"},
                         {"longitude":"-74.04255520552397","phone":"3048889698","latitude":"4.677961524290578","address":"Calle 96","name":"Lució "},
                         {"phone":"3045559887","longitude":"-74.04200468212366","latitude":"4.675787146576546","address":"Calle 94A","name":"kilpp"}],
              "type_service" : "36",
              "client_id" : "672",
              "platform" : "Android",
              "description_text" : "Diligencia prueba",
              "time" : 350,
              "distance" : 3480,
              "pay" : 5,
              "amount" : 12740,
              "samepoint" : "true"}';*/

/*$data = array(
    'auth_trans_ref_no' => '14546987',
    'decision' => 'ACCEPT',
    'message' => 'Pago confirmado correcto',
    'reason_code' => 100,
    'req_reference_number' => 269
);*/
/*
$data =  Array
(
    "key" => "3edcdb20e0030daab21d0ba9af4c0dc2",
    "distance" => '0.9 Km',
"origin_client" => 123,
    "origin_address" => 'Kr 19 #114a-59, Bogotá, Colombia',
    "origin_latitude" => '4.6983011687527',
    "origin_longitude" => '-74.050260186195',
    "destiny_address" => "Cra. 21 #105-1 a 105-91, Bogotá, Colombia",
    "destiny_latitude" => '4.6930723771913',
    "destiny_longitude" => '-74.053516387939',
    "amount" => 10200,
    "type_service" => 34,
    "pay" => 3,
    "time" => 61,
    "platform" => 'web',
"email_destiny_client" => 'tech@tech.tec',
"destiny_name" => 'tech tech',
"cellphone_destiny_client" => 1111555,
    "content_pack" => 0,
    "bag_id" => 25,
    "origin_detail" => 'torre 345',
    "destiny_detail" => '',
    "tip" => 3500,
    "polyline" => "kqp[he~bMoAi@f@kAjAqCjBiExCgHfAiCdGdC|Bx@w@lBoAvCaEpJ"
);
*/
/*$data = Array(
    "key" => "$2y$10$015rZIJUalREKjZ.3GlHIuqbDb1AeXh4ZQABIY6uzGRHV1FPgNJF",
    "shipping_id" => "651",
    "point_id" => "538",
    "state" => 1
);*/
/*
$data =  Array
(
    "key" => "3edcdb20e0030daab21d0ba9af4c0dc2",
    "usr" => "testnext",
    "pass" => "71c3aadf53314951e5ffdc5a45a1cf02",
    "uuid" => "dM8QxayeIgE:APA91bGjHxjSSXOwO7HoHhvFuKueZhNngFzQnEXUawIdtU-eoJoW29rVYenH7XfD6nx6svkFWWgPMwFdrqGTffjPuxkdYAIE5F8R_oWFq4wPGwLzVP67YqAukjDote0HR5Ag1j_fheER",
    "latitude" => "4.676474",
    "longitude" => "-74.051539"
);
*/
/*$data =  Array
(
    "key" => "3edcdb20e0030daab21d0ba9af4c0dc2",
    "origin_latitude" => "4.7067953",
    "origin_longitude" => "-74.0543218",
    "destiny_latitude" => "4.6623300479002",
    "destiny_longitude" => "-74.074154309928",
    "bag_id" => "25",
    "type_service" => "34",
    "tip" => "2000",
    "user_id" => "335"
);*/
$additional[] = "1";
$additional[] = "3";
$products[] = [
                "id" => "1",
                "additional" => []
              ];
$products[] = [
    "id" => "2",
    "additional" => $additional
];


$products = json_encode($products);
$data =  Array
(
    "key" => "1fc0f5604616c93deac481b33989f10e",
    "type_service" => "62",
    "pay" => "5",
    "id_delivery" => "4",
    "products" => $products,
    "id_location" => "93"
);

$data ='{"key" : "21569d3e6977ae51178544f5dcdd508652799af3.IVadPml3rlEXhUT13N1QhlJ5mvM=",
         "trackings": [{"tracking":"9853453298"},
                      {"tracking":"9713067342"},
                      {"tracking":"9676812128"}],
              "name" : "Orden el tiempo dos",
              "description" : "primera orden dos" }';

/*$data ='{"key" : "21569d3e6977ae51178544f5dcdd508652799af3.IVadPml3rlEXhUT13N1QhlJ5mvM=",
         "locations": [{"id_client":"679","latitude":"4.762044","longitude":"-74.040132","address":"calle esperanza falsa",
                        "details":"apto 1021","city":"35"},
                      {"address":"calle av 1475","email":"test@prueba.it","cellphone":"3007891240","name":"Mr test",
                       "identification":"912354703"}],
              "name" : "Ruta prueba",
              "description" : "Ruta para el cargue de mensajero",
              "resource_id" : 155 }';*/

//$data = json_decode($data);
//echo $data->key;die;
/*foreach ($routeResource->getMessages() as $message) {
                                echo $message."<br>";
                            }
}*/

echo "<pre>".print_r($data,1)."</pre>";
$url = "http://localhost/api_devel/service/createServiceOrder";
//$url = "localhost/api/shipping/post";
//$url = "localhost/losgiticapp/backup/devel/api/deliveryverification/post";
//$url = "http://52.43.247.174/api_devel/shipping/quotation";
//$url = "localhost/losgiticapp/backup/devel/api/Diligence/diligencesupdatepoints";
/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$serverOutput = curl_exec ($ch);
curl_close ($ch);
print_r($serverOutput);
die;*/
$pass = "4l3j4ndr0";
$pass =  hash('sha256', $pass);
/*$pass = base64_encode($pass);//codificamos la cadena en bas64
$pass = strrev($pass);//ponemos la cadena al reves
$pass = md5($pass);//codificamos a md5 la cadena*/


echo $pass;
?>


