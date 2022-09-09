<title>FCM Push for Shorts App</title>

<?php

    date_default_timezone_set('Asia/Kolkata');
    $currentTime = date( 'd-m-Y h:i:s A', time () );
    
    $title = $_GET['title'];
    $body = $_GET['body'];
    $image = $_GET['image'];
    
      if(empty($title)) {
        $title = date('F d, Y', strtotime($currentTime));
        $body = "Now time: " . date('H:i:s A', strtotime($currentTime));
        $image = "https://img-b.udemycdn.com/course/750x422/4015018_0000_58.jpg";
        $link = "https://google.com";}
    
    $data = [
        "notification" => [
            "android_channel_id" => "high_importance_channel",
            "body"  => $body,
            "title" => $title,
            "image" => $image,
            "alert" => true,
        ],
        "priority" => "high",
        "data" => [
            // "click_action"  =>  "FLUTTER_NOTIFICATION_CLICK",
            // "id"            =>  "1",
            // "status"        =>  "done",
            "info"          =>  [
                "title" => $title,
                "image" => $image
            ]
        ],
        // "to" => "dqKiKelcRMi_D3aDsbTEtB:APA91bEQi3br-doo32lYDhq4VnkNJ5_hyOUSLK32UR2JJ_9QsOAgohv5fJ6j8taGM8JBmCr6sjJCU5g7Ck3kOC4NMRWOIEHyFW9NYhshQdvnLnm6W5bjV6SlxnN3a6Zzg_WD99K4docS"
        "to" => "/topics/all"
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_POST, 1);

    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key=AAAANROsRnk:APA91bGQnk87h_lXG9hwD0eKI3RgkkXHCQ62xqZO-1vMDQv8QE2eoq1HT9Yom6FtqjteojcrBjhGNR8W8ntClVEc_nOk0dp-UVDANf8pnNIVu2g-UPRv6s7qigNTSYGbO6Q7nam5fJLf';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close ($ch);
    
    echo "Notification sent!";
?>