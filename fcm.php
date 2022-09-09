<title>FCM Push</title>

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
        "priority" =>  "high",
        "data" => [
            // "click_action"  =>  "FLUTTER_NOTIFICATION_CLICK",
            // "id"            =>  "1",
            // "status"        =>  "done",
            "info"          =>  [
                "title"  => $title,
                "link"   => $link,
                "image"  => $image
            ]
        ],
        "to" => "/topics/all"
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_POST, 1);

    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key=AAAAU48kWFQ:APA91bG_hcpJkh9WifkmNbUuy66J8giy2EcCxztFHPTA0cebrWQ09I05HjC3pOGXUoC8-rODx-qIB0UU2Y_w_jyZMzQRrgwVWIsZhNIFywG5aERk4-fdAvsM_PCa-NixLIAg7dROfta0';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close ($ch);
    
    echo "Notification sent!";
    // AAAAfDXXMPY:APA91bHVtWSX5ofZf_15cQ1-3gj711ak-B3tjBPpT_JAA_fQ_4kZ_nX5TXueNaL5so8nZ6Nf66pCxknUF3-k8QYTou9xx5fCk4Sb48Mf_SyD9cpkSlpqKWtMH0k3o4-LoydZeueTeqqK
?>