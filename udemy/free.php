<?php
error_reporting(0);
include("simple_html_dom.php");
include("du-disc-image.php");

header('Content-Type: application/json');
$l = 14;
$p = $_GET['p'];

$cat = 'free';

$posts = [];

// Machine start here
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://comidoc.net/free?page='.$p); // target
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // provide a user-agent
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow any redirects
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the result
$result = curl_exec($ch);
curl_close($ch);
$site_html = str_get_html($result);
///////////////////////////////////////

if($p=='1') {
  $jsonObj2 = json_decode('{}');
  $jsonObj2->title = 'Udemy Sale: All Courses up to 90% OFF Now!';
  $jsonObj2->thumbnail = $ud_image;
  $jsonObj2->topic = 'Udemy Discount';
  $jsonObj2->url = '/rakuten/';
  $jsonObj2->isLocked = false;

  $posts [] = $jsonObj2; 
}


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
            $topic = 'Free';
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
 
echo json_encode($posts, JSON_PRETTY_PRINT);

?>	