<?php
error_reporting(0);
include("simple_html_dom.php");
header("Access-Control-Allow-Origin: *"); //accept cross origin
header('Content-Type: application/json');
$l = 14;
$p = $_GET['p'];

$cat = 'coupons';

$posts = [];

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
     for($i = 0; $i <= $l; $i++) {
      //2. store every post url in to array
        $a = $site_html->find('a .flex.tracking-widest')[$i];
         
         if($a) {
            $title = $site_html->find('h2')[$i]->innertext;
            $thumbnail = $site_html->find('a .relative picture img')[$i]->src;
            if($a->innertext === '100% OFF') {
                $topic = '100% OFF';
                
            $url = $a->parent->href;
            
            $jsonObj = json_decode('{}');
            $jsonObj->title = html_entity_decode($title, ENT_QUOTES);;
            $jsonObj->thumbnail = $thumbnail;
            $jsonObj->topic = html_entity_decode($topic, ENT_QUOTES);
            $jsonObj->url = $url;
            
            if(($i+1) % 5 === 0) {$jsonObj->isLocked = true;} else {$jsonObj->isLocked = false;}
            
            $posts [] = $jsonObj;
         }
         
        }
         
     }
     
 }
 
echo json_encode($posts, JSON_PRETTY_PRINT);

?>	