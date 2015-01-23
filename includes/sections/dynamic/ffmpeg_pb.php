<?php

// http://stackoverflow.com/questions/11441517/ffmpeg-progress-bar-encoding-percentage-in-php

if(!is_file(dirname(dirname(dirname(__FILE__))) . "/ffmpeg_info.txt")) {
    die();
}

$content = @file_get_contents(dirname(dirname(dirname(__FILE__))) . "/ffmpeg_info.txt");

if($content){
    //get duration of source
    preg_match("/Duration: (.*?), start:/", $content, $matches);

    $rawDuration = $matches[1];

    //rawDuration is in 00:00:00.00 format. This converts it to seconds.
    $ar = array_reverse(explode(":", $rawDuration));
    $duration = floatval($ar[0]);
    if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
    if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;

    //get the time in the file that is already encoded
    preg_match_all("/time=(.*?) bitrate/", $content, $matches);

    $rawTime = array_pop($matches);

    //this is needed if there is more than one match
    if (is_array($rawTime)){$rawTime = array_pop($rawTime);}

    //rawTime is in 00:00:00.00 format. This converts it to seconds.
    $ar = array_reverse(explode(":", $rawTime));
    $time = floatval($ar[0]);
    if (!empty($ar[1])) $time += intval($ar[1]) * 60;
    if (!empty($ar[2])) $time += intval($ar[2]) * 60 * 60;

    //calculate the progress
    $progress = round(($time/$duration) * 100);

    $temp = $time/$duration;

    $readable_time = floor($time/60) . ":" . sprintf("%02d", floor($time) % 60);
}

?>

<div class="progress_bar" style="width: calc(100vw * <?php echo $temp; ?> - 73px);"></div>
<div class="time"><?php echo $readable_time; ?></div>