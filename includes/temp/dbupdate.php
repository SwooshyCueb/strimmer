<?php

ini_set("display_errors", "On");
ini_set("display_startup_errors", "On");
ini_set("error_reporting", "E_ALL");
ini_set("html_errors", "On");
ini_set("log_errors", "On");


// -- SQL --
// hostname to connect to
$hostname = "theblackparrot.us";
$port = 3306;
// SQL credentials
$user = "mpd_user";
$password = "SOMEPASS";
// database that stores info for the cache list
$database = "mpd";
// defines the SQL connection
$mysqli = new mysqli($hostname, $user, $password, $database, $port);

function soundcloud_resolveFromURL($track_url) {
	if(isset($track_url)) {
		$client_id = "SOMEKEY";
		$url = "http://api.soundcloud.com/resolve.json?url=" . $track_url . "&client_id=" . $client_id;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   

		$output = curl_exec($curl);
		curl_close($curl);

		return $output;
	}
}

function soundcloud_getStreamVars($location) {
	if(isset($location)) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $location);
		curl_setopt($curl, CURLOPT_HEADER, 0);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   

		$output = curl_exec($curl);
		curl_close($curl);

		return $output;
	}
}

$query = "SELECT * FROM db";
$result = mysqli_query($mysqli,$query);
while($row = mysqli_fetch_array($result)) {
	$svc   = $row['SERVICE'];
	switch ($svc) {
		case 'WYZL':
			$trkid = $row['SERVICE_ARG1'];
			$query2 = "UPDATE db
				SET
				TRACKID='" . $svc . $trkid . "'
				WHERE
				SERVICE='" . $svc . "' AND
				SERVICE_ARG1='" . $trkid . "';";
			break;
		case 'SDCL':
			$resolved_vars = json_decode(soundcloud_resolveFromURL($row['SERVICE_ARG1']),true);
			$stream_vars = json_decode(soundcloud_getStreamVars($resolved_vars['location']),true);
			$trkid = $stream_vars['id'];
			$query2 = "UPDATE db
				SET
				TRACKID='" . $svc . $trkid . "',
				SERVICE_ARG2='" . $trkid . "'
				WHERE
				SERVICE='" . $svc . "' AND
				SERVICE_ARG1='" . $row['SERVICE_ARG1'] . "';";
			break;
		default:
			break;
	}
	
	echo $query2;
	echo '<br />';
	$result2 = mysqli_query($mysqli,$query2);
	var_dump($result2);
	echo '<br /><br />';
}


$query = "SELECT * FROM db_cache";
$result = mysqli_query($mysqli,$query);
while($row = mysqli_fetch_array($result)) {
	$svc   = $row['SERVICE'];
	$trkid = $row['RETURN_ARG1'];
	$query2 = "UPDATE db_cache
		SET
		TRACKID='" . $svc . $trkid . "'
		WHERE
		SERVICE='" . $svc . "' AND
		RETURN_ARG1='" . $trkid . "';";
	echo $query2;
	echo '<br />';
	$result2 = mysqli_query($mysqli,$query2);
	var_dump($result2);
	echo '<br /><br />';
}

?>