<?php

header("Content-Type: text/plain;");

$login  ='a614a4ac6bfb282d47cdacfa02b72888';
$trankey='XKLOBMnoLc925h7X';
$seed   = date('c');

try {
    $webservice = new \SoapClient ('https://api.placetopay.com/soap/placetopay/?wsdl',  
    array(
        'exceptions' => true, 
        'features'   => SOAP_SINGLE_ELEMENT_ARRAYS,
        'trace' => true
        )
    );
    
    //autenticacion
    
    $autenticacion = new stdClass();
    $autenticacion->login = $login;
    $autenticacion->seed = date('c');
    $autenticacion->tranKey = sha1($autenticacion->seed.$trankey, false);
    $autenticacion->additional = null;

    // Informacion de la tarjeta
    
    $cardinfo = new stdClass();
    $cardinfo->number       = 4111111111111111;
    $cardinfo->type         = 'C';
    //$cardinfo->expiration   = '1219';//AAAAMMDD
    $cardinfo->differed     = 1;
    $cardinfo->secureCode   = 123;
    
    //Información del tarjeta habiente
    
    $owner = new stdClass();
    $owner->documentType="CC";
    $owner->document="1030629404";
    $owner->firstName="andresrodriguez";
    $owner->emailAddress="andres@mail.com";
    $owner->phone=3057063280;
    $owner->mobile=3057063280;
    
    //invocar el servicio
    
    $tokenizeCard = new stdClass();
    $tokenizeCard->auth = $autenticacion;
    $tokenizeCard->cardInfo = $cardinfo;
    $tokenizeCard->owner = $owner;
    
    $queryTransactiontokenize = $webservice->tokenizeCard($tokenizeCard);
    print_r($queryTransactiontokenize);
    
    
}
catch (SoapFault $e) {
	print_r($e);
	echo "catch 1";
}