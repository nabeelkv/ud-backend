<?php
// error_reporting(0);
include("simple_html_dom.php");

header('Content-Type: application/json');
$l = 14;
$p = $_GET['p'];
$posts = [];

// Machine start here
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.discudemy.com/language/English/' . $p); // target
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
            $title = $site_html->find('.content a.card-header')[$i]->innertext;
            $thumbnail = $site_html->find('.ui.full-width.image')[$i]->src;
            $topic = $site_html->find('span.catSpan')[$i]->innertext;
            $isPaid = $site_html->find('.meta')[$i]->innertext;
            if(strpos($isPaid, '-&gt;') !== false) {$isPaid='100% OFF';} else {$isPaid='Free';}
            $url = substr(strrchr($site_html->find('.card-header')[$i]->href, '/'), 1);
            
            $jsonObj = json_decode('{}');
            $jsonObj->title = html_entity_decode($title, ENT_QUOTES);
            $jsonObj->thumbnail = $thumbnail;
            $jsonObj->topic = $isPaid;
            $jsonObj->url = $url;
            
            if($i % 5 === 0) {$jsonObj->isLocked = true;} else {$jsonObj->isLocked = false;}
            
            $posts [] = $jsonObj;
         
     }
     
 }
 
echo json_encode($posts, JSON_PRETTY_PRINT);
?>	