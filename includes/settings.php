<?php
// -- SQL --
// hostname to connect to
$hostname = "localhost";
// SQL credentials
$user = "mpd_user";
$password = "SOMEPASS";
// database that stores info for the cache list
$database = "mpd";
// defines the SQL connection
$mysqli = new mysqli($hostname, $user, $password, $database);

// -- MPD --
$mpd['host'] = "theblackparrot.us";
$mpd['port'] = 6600;
$mpd['password'] = "SOMEPASS";

// -- SOUNDCLOUD --
// API key
//$client_id = "SOMEKEY";