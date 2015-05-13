<?php
// -- Program settings --
$prog_title = "Strimmer";
$prog_title_short = "strimmer";

// -- Email settings --
$email['alerts_enabled'] = 0;
$email['to'] = "example@example.com";
$email['from'] = "example@example.com";

// -- Debug settings --
// see http://php.net/manual/en/errorfunc.configuration.php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);
ini_set("log_errors", 1);
ini_set("log_errors_max_len", 2048);
ini_set("ignore_repeated_errors", 1);
ini_set("ignore_repeated_source", 0);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
ini_set("error_log", dirname(dirname(__FILE__)) . "/php.log");

// -- Session settings --
ini_set('session.gc_maxlifetime', 21600);
session_set_cookie_params(21600);

// -- SQL --
// hostname to connect to
$sql['host'] = "localhost";
$sql['port'] = 3306;
// SQL credentials
$sql['user'] = "strimmer_user";
$sql['pass'] = "SQL_PASS_HERE";
// database that stores info for the cache list
$sql['db'] = "strimmer";
// defines the SQL connection
$mysqli = new mysqli($sql['host'], $sql['user'], $sql['pass'], $sql['db'], $sql['port']);

// -- Icecast --
$icecast['host'] = "localhost";
$icecast['public_url'] = "example.com";
$icecast['port'] = 8000;
$icecast['pass'] = "ICECAST_PASS_HERE";
// number 0 - 9, dictating stream quality; lower=better
// see https://trac.ffmpeg.org/wiki/Encode/MP3#VBREncoding
$icecast['qual'] = 6;
$icecast['quallq'] = 9;
$icecast['qual_opus'] = 48000;
$icecast['quallq_opus'] = 24000;
// description of variable here
$icecast['mount'] = "stream.mp3";
$icecast['mountlq'] = "streamlq.mp3";
$icecast['mount_opus'] = "stream.opus";
$icecast['mountlq_opus'] = "streamlq.opus";
$icecast['admin_user'] = "strimmer_user";
$icecast['admin_pass'] = "ICECAST_ADMIN_PASS_HERE";
// ffmpeg compatible transcoder
$icecast['ffmpeg'] = "ffmpeg";
// ffprobe compatible stream information viewer
$icecast['ffprobe'] = "ffprobe";
// Public stream URL
$icecast['url'] = 'http://' . $icecast['public_url'] . ':' . $icecast['port'] . '/' . $icecast['mount'];
$icecast['url_lq'] = 'http://' . $icecast['public_url'] . ':' . $icecast['port'] . '/' . $icecast['mountlq'];
$icecast['url_opus'] = 'http://' . $icecast['public_url'] . ':' . $icecast['port'] . '/' . $icecast['mount_opus'];
$icecast['url_opuslq'] = 'http://' . $icecast['public_url'] . ':' . $icecast['port'] . '/' . $icecast['mountlq_opus'];
// -- SOUNDCLOUD --
// API key
$sc_api_key = "YOUR_SOUNDCLOUD_API_KEY_HERE";

// -- JAMENDO --
//API key
$jm_api_key = "YOUR_JAMENDO_API_KEY_HERE";

// -- HYPE MACHINE --
// Username
$hypem['user'] = "YOUR_HYPE_MACHINE_USERNAME_HERE";
// Password
$hypem['pass'] = "YOUR_HYPE_MACHINE_PASSWORD_HERE";

// YOUTUBE BACKEND REQUIRES youtube-dl
// (see about including a version of it?)

?>