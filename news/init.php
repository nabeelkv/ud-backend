<?php 

include "config/config.php";
// session_start();

spl_autoload_register(function($className) { include "classes/$className.php"; });

// error_reporting(0);
// ini_set('display_errors', 0);

date_default_timezone_get();  //this set GMT time
$currentDateTime = date("Y-m-d H:i:s");

?>
