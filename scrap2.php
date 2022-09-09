<?php 

include("simple_html_dom.php");

// $html = file_get_html('https://.to/');
    
// echo $html;


$url = $_GET['url'];


// Start a cURL resource
$ch = curl_init();
// Set options for the cURL
curl_setopt($ch, CURLOPT_URL, $url); // target
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // provide a user-agent
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow any redirects
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the result
// Execute the cURL fetch
$result = curl_exec($ch);
// Close the resource
curl_close($ch);
// Output the results

$html = str_get_html($result);

echo $html;


?>