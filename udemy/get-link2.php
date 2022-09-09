<?php
error_reporting(0);
include("simple_html_dom.php");

header('Content-Type: application/json');
$courseData = array();
$url = $_GET['url'];

         
         $filter_udemy_link = str_replace("/udemy/", "",$url);
         $udemy_link = 'https://www.udemy.com/course/'.$filter_udemy_link.'/';
         
         $jsonObj = json_decode('{}');
         $jsonObj->url = $udemy_link;
         
         $courseData [] = $jsonObj;
       
       
echo json_encode($courseData, JSON_PRETTY_PRINT);
?>