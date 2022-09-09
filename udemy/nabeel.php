<?php
include("simple_html_dom.php");
header("Access-Control-Allow-Origin: *"); //accept cross origin
// header('Content-Type: application/json');

$url = $_GET['url'];

     // Machine start here
        // Machine start here
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://comidoc.net/'.$url); // target
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
         $couponTable = $site_html->find('#__NEXT_DATA__');
         print_r($couponTable);
       }
       
       
// echo json_encode($courseData, JSON_PRETTY_PRINT);
?>