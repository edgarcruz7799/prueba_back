<?php
/**
 * Created by PhpStorm.
 * User: Jhon Rengifo
 * Date: 30/11/16
 * Time: 04:51 PM
 */

//$host    = "172.31.21.96";//$argv[2]; //ip de la página
$host    = "35.161.127.232";//ip de la página
$port    = $socket;
$message = $token;//$argv[1];
// create socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");
// connect to server
$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");
// send string to server
socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
// get server response
$result = socket_read ($socket, 1024) or die("Could not read server response\n");
//echo "Reply From Server  :".$result;
// close socket
socket_close($socket);