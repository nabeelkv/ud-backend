<?php

include("init.php");

function humanTiming ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}



function expireDate($time) {
    
$date = strtotime($time);
$remaining = $date - time();

$days_remaining = floor($remaining / 86400);
$hours_remaining = floor(($remaining % 86400) / 3600);
$mins_remaining = floor(($remaining % 3600) / 60);
$sec_remaining = ($remaining % 60);

if($days_remaining >= 1) {

	return $days_remaining > 1 ? "$days_remaining days" : "$days_remaining day";

} else if($hours_remaining >= 1) {

	return $hours_remaining > 1 ? "$hours_remaining hours" : "$hours_remaining hour";

} else if($mins_remaining >= 1) {

	return $mins_remaining > 1 ? "$mins_remaining minutes" : "$mins_remaining minute";

} else if($sec_remaining >= 1) {

	return $sec_remaining > 1 ? "$sec_remaining minutes" : "$sec_remaining minute";

} else {

	return "Expired";
}
}




 ?>