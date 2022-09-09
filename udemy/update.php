<?php
include("simple_html_dom.php");

header('Content-Type: application/json');
$appData = array();
$currentVersion = $_GET['version'];
$currentVersion = (int) $currentVersion;
$latestVersion = 0;

$jsonObj = json_decode('{}');

 if($currentVersion < $latestVersion) {
    $jsonObj->update = '1';
  } else {
    $jsonObj->update = '0';
 }
         
$appData [] = $jsonObj;
       
       
echo json_encode($appData, JSON_PRETTY_PRINT);
?>