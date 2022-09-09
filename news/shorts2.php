<?php

include("init.php");
$dbQueries = new dbQueries;
header('Content-Type: application/json');

$page = $_GET['page'] - 1;
$cat_id = $_GET['cat_id'];

$dbQueries->Query("SELECT * FROM news WHERE cat_id = $cat_id ORDER BY created DESC LIMIT $page,1");
$newsList = $dbQueries->fetchAll();
   
echo json_encode($newsList, JSON_PRETTY_PRINT);
?>	
