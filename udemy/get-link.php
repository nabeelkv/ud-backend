<?php
error_reporting(0);
include("simple_html_dom.php");
header("Access-Control-Allow-Origin: *"); //accept cross origin
header('Content-Type: application/json');
$courseData = array();
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
         $couponTable = $site_html->find('tr.text-center.text-th-primary-medium');
         $filter_udemy_link = str_replace("/udemy/", "",$url);
         $workingCoupon = end($couponTable);
         $find_udemy_coupon_code = $workingCoupon->find('td')[2]->plaintext;
         $udemy_link = 'https://click.linksynergy.com/deeplink?id=FNxb/pO*KcU&mid=39197&murl=https://www.udemy.com/course/'.$filter_udemy_link.'/?couponCode='.$find_udemy_coupon_code;
         
         $jsonObj = json_decode('{}');
         $jsonObj->url = $udemy_link;
         
         $courseData [] = $jsonObj;
       }
       
       
echo json_encode($courseData, JSON_PRETTY_PRINT);
?>