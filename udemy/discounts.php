<?php
error_reporting(0);
include("simple_html_dom.php");

header('Content-Type: application/json');
$l = 14;
$p = $_GET['p'];

$cat = 'coupons';

$posts = [];

if($p === '1') {
// Machine start here
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://comidoc.net/coupons?page='.$p); // target
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // provide a user-agent
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow any redirects
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the result
$result = curl_exec($ch);
curl_close($ch);
$site_html = str_get_html($result);
///////////////////////////////////////

//Manage machine here
if(!$site_html) {
     
     echo "Unable to load Udemy website";
     
 } else {
     
     // 1. Find all post links
     for($i = 0; $i <= 1; $i++) {
            
            $jsonObj = json_decode('{}');
            $jsonObj->title = 'title';
            $jsonObj->thumbnail = 'https://img-c.udemycdn.com/notices/web_banner/image_udlite/4f9d4123-43ee-4f2a-b5ef-1f2ac22962f3.jpg';
            $jsonObj->topic = 'title';
            $jsonObj->url = 'title';
            
           $jsonObj->isLocked = true;
            
            $posts [] = $jsonObj;
         
     }
     
 }
}
 
echo json_encode($posts, JSON_PRETTY_PRINT);

?>	