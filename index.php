<?php
/*
*the program for ussd for basic functionality
*/
session_start();
//declaring the variables to obtain customer phone, ussd code
header('Content-type: text/plain');
// header('Content-Type: application/json');
$choice = $_GET['USSD_STRING'];
$phoneNumber = $_GET['MSISDN'];
$serviceCode = $_GET['serviceCode'];
$level1 = 0;
$level = explode("*", $choice);
$level1 = count($level);

//including the connection page
include_once 'connection.php';

if(isset($choice)){
  //if empty choice
  if($choice == ""){
    echo "SELECT LANGUAGE:\n1.English\n2.Swahili";
  }
  else if(isset($level[0])){
    switch ($level[0]) {
      case 1:
        include_once "english.php";
        break;
      case 2:
        include_once "swahili.php";
        break;

      default:
        echo "Invalid Input";
        break;
    }
  }
}

 ?>
