<?php

date_default_timezone_set("Asia/Kolkata");
$currentDateTime = date("m-d H:i");

function sendNotif($to, $notif) {
   $apiKey = "AAAAZIbZljQ:APA91bGvcAzOIdGolt19oTXO4iEtcILJGg7KJSAhiBINIx1mCUYiZYKQzxKkQKZ610jdJX3GM1Lc4kOGYkkVO-E8TSMqWSOU3Xqd2y8msExFvp2G8eL0I15myVgku_ekglqTJzxUFQsy";


   $ch = curl_init();

   $url = "https://fcm.googleapis.com/fcm/send";

   $fields = json_encode(array('to'=>$to, 'notification'=>$notif));

   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);


   $headers = array();
   $headers[] = 'Authorization: key ='.$apiKey;
   $headers[] = 'Content-Type: application/json';
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

   $result = curl_exec($ch);
   if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
   }
   curl_close($ch);

}


$to = "/topics/news";
$notif = array(
	'title' => 'FCM from 000webhost',
	'body' => $currentDateTime,
);


sendNotif($to, $notif);


?>