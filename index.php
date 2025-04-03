<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallpaper Based on Sun's Position in Jordan</title>
    <link rel="stylesheet" href="./assets/styles/index.css">

</head>
<body>
<?php

function getSunriseSunsetTimes($latitude, $longitude) {
    $url = "https://api.sunrise-sunset.org/json?lat=$latitude&lng=$longitude&formatted=0";  
    $response = file_get_contents($url);  
    $data = json_decode($response, true);  

    return [
        'sunrise' => $data['results']['sunrise'],
        'sunset' => $data['results']['sunset']
    ];
}
function convertToLocalTime($time) {
    $dateTime = new DateTime($time, new DateTimeZone('UTC'));  
    $dateTime->setTimezone(new DateTimeZone('Asia/Amman'));  
    return $dateTime;
}


function selectWallpaper($latitude, $longitude) {
 
    $times = getSunriseSunsetTimes($latitude, $longitude);  
    $currentTime = new DateTime();  
    $currentTime->setTimezone(new DateTimeZone('Asia/Amman'));  
    $sunrise = convertToLocalTime($times['sunrise']);  
    $sunset = convertToLocalTime($times['sunset']);  

    if ($currentTime < $sunrise || $currentTime >= $sunset) {
       
        return "assets/pix/night.png";
    }

    
    if ($currentTime >= $sunrise && $currentTime < $sunrise->modify('+3 hours')) {
        return "assets/pix/sunrise.png";
    }

    if ($currentTime >= $sunrise && $currentTime < $sunset->modify('-1 hour')) {
        return "assets/pix/noon.png";
    }

    
    if ($currentTime >= $sunset->modify('-1 hour') && $currentTime < $sunset) {
        return "assets/pix/evening.png";
    }

    return "assets/pix/sunset.png";
}

$latitude =31.963158;
$longitude = 35.930359;
$selectedWallpaper = selectWallpaper($latitude, $longitude);
$times = getSunriseSunsetTimes($latitude, $longitude);
$currentTime = new DateTime();
$currentTime->setTimezone(new DateTimeZone('Asia/Amman'));
$sunrise = convertToLocalTime($times['sunrise']);
$sunset = convertToLocalTime($times['sunset']);
echo "<script>document.body.style.backgroundImage = 'url($selectedWallpaper)';</script>";

echo "<div class='overlay'>
     <span>Current time: " . $currentTime->format('H:i') . "</span>
     <span>Sunrise time: " . $sunrise->format('H:i') . "</span>
     <span>Sunset time: " . $sunset->format('H:i') . "</span>
</div>";

?>
</body>
</html>
