<?php

$mpd['host'] = "theblackparrot.us";
$mpd['port'] = 6600;
$mpd['password'] = "SOMEPASS";
$playlist = explode("\n",str_replace("\n\r","\n",shell_exec("mpc -f \"%file%\" -h " . $mpd['host'] . " -p " . $mpd['port'] . " playlist")));

var_dump($playlist);

?>