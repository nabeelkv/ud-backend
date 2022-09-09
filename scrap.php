<?php

include("simple_html_dom.php");

$url = $_GET['url'];

$html = file_get_html("$url");
echo $html;

?>