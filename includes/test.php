<?php

include_once "settings.php";
include_once "functions.php";

/*$query = "SELECT * FROM db WHERE ID=1";
$result = mysqli_query($mysqli,$query);

while($row = mysqli_fetch_array($result)) {
	echo(var_dump($row));
	echo "<br/>";

	$resolved_vars = json_decode(soundcloud_resolveFromURL($row['SERVICE_ARG1']),true);
	echo(var_dump($resolved_vars) . "<br/><br/>");
	echo $resolved_vars["location"] . "<br/><br/>";
	$stream_vars = json_decode(soundcloud_getStreamVars($resolved_vars['location']),true);
	$user_vars = $stream_vars['user'];
	echo "<pre>";
	print_r($stream_vars);
	echo "</pre>";
	echo "<pre>";
	print_r($user_vars);
	echo "</pre>";
}*/

echo "<pre>";
print_r(mpd_getPlaylist());
echo "</pre>";