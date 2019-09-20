<?php
//declaring the connection variables
$host = "localhost";
$user = "root";
$passwd = "";
$db = "project_ussd";

$conn = new mysqli($host,$user,$passwd,$db);

if($conn->connect_error){
	die("Connection Failed" .$conn->connect_error);
}

 ?>
