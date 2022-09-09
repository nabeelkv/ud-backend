<?php

include("init.php");
$dbQueries = new dbQueries;
header('Content-Type: application/json');

$page = $_GET['_page'];

$dbQueries->Query("SELECT * FROM photos LIMIT $page,10");
$photos = $dbQueries->fetchAll();
   
echo json_encode($photos, JSON_PRETTY_PRINT);
?>	
