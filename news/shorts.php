<?php

include("init.php");
$dbQueries = new dbQueries;
header('Content-Type: application/json');

$page = $_GET['page'] - 1;
$cat_id = $_GET['cat_id'];

if($cat_id !== '0') { $db_cat_query = "WHERE cat_id = $cat_id";} else { $db_cat_query = "";}

$dbQueries->Query("SELECT * FROM news $db_cat_query ORDER BY created DESC LIMIT 10 offset $page");
$newsList = $dbQueries->fetchAll();
   
echo json_encode($newsList, JSON_PRETTY_PRINT);
?>	
