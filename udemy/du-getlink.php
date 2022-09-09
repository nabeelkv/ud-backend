<?php
include("simple_html_dom.php");

header('Content-Type: application/json');
$courseData = array();
$url = $_GET['url'];

     // Machine start here
        // Machine start here
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://www.discudemy.com/go/'.$url); // target
      curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // provide a user-agent
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow any redirects
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the result
      $result = curl_exec($ch);
      curl_close($ch);
      $site_html = str_get_html($result);
      ///////////////////////////////////////

       if(!$site_html) {
           echo "error";
       } else {
           
         $udemy_link = $site_html->find('.ui.segment a')[0]->href;
         $udemy_link = substr($udemy_link, strpos($udemy_link, "url="));
         $jsonObj = json_decode('{}');
         $jsonObj->url = $udemy_link;
         
         $courseData [] = $jsonObj;
       }
       
       
echo json_encode($courseData, JSON_PRETTY_PRINT);
?>