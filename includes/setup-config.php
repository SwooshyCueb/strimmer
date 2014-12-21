<?php

$here = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if ((!empty($_POST)) && ($_POST['setup'] == 'config')) {
	// Actually setup config.php here.
	$config_str = file_get_contents(dirname(dirname(__FILE__)) . "/config-sample.php");

	if ($config_str == FALSE) {
		header('HTTP/1.0 500 Internal Server Error');
		echo "Could not open config-sample.php";
		die();
	}

	$config_str = preg_replace("/(prog_title = \").+(\";)/", "\\1" . $_POST['title'] . "\\2", $config_str);
	$config_str = preg_replace("/(prog_title_short = \").+(\";)/", "\\1" . $_POST['idstr'] . "\\2", $config_str);

	$config_str = preg_replace("/(ini_set\('session.gc_maxlifetime',) [0-9]+(\);)/", "\\1 " . $_POST['session_timeout'] . "\\2", $config_str);
	$config_str = preg_replace("/(session_set_cookie_params)\([0-9]+(\);)/", "\\1(" . $_POST['cookie_timeout'] . "\\2", $config_str);

	$config_str = preg_replace("/(sql\['host'\] = )\".+(\";)/", "\\1\"" . $_POST['sql_host'] . "\\2", $config_str);
	$config_str = preg_replace("/(sql\['port'\] =) [0-9]{1,5}(;)/", "\\1 " . $_POST['sql_port'] . "\\2", $config_str);
	$config_str = preg_replace("/(sql\['user'\] = )\".+(\";)/", "\\1\"" . $_POST['sql_user'] . "\\2", $config_str);
	$config_str = preg_replace("/(sql\['pass'\] = )\".+(\";)/", "\\1\"" . $_POST['sql_pass'] . "\\2", $config_str);
	$config_str = preg_replace("/(sql\['db'\] = )\".+(\";)/", "\\1\"" . $_POST['sql_db'] . "\\2", $config_str);

	$config_str = preg_replace("/(mpd\['host'\] = )\".+(\";)/", "\\1\"" . $_POST['mpd_host'] . "\\2", $config_str);
	$config_str = preg_replace("/(mpd\['port'\] =) [0-9]{1,5}(;)/", "\\1 " . $_POST['mpd_port'] . "\\2", $config_str);
	$config_str = preg_replace("/(mpd\['password'\] = )\".+(\";)/", "\\1\"" . $_POST['mpd_pass'] . "\\2", $config_str);

	$config_str = preg_replace("/(icecast\['host'\] = )\".+(\";)/", "\\1\"" . $_POST['ic_host'] . "\\2", $config_str);
	$config_str = preg_replace("/(icecast\['port'\] =) [0-9]{1,5}(;)/", "\\1 " . $_POST['ic_port'] . "\\2", $config_str);
	$config_str = preg_replace("/(icecast\['pass'\] = )\".+(\";)/", "\\1\"" . $_POST['ic_stream_pass'] . "\\2", $config_str);
	$config_str = preg_replace("/(icecast\['qual'\] =) [0-9](;)/", "\\1 " . $_POST['ic_qual'] . "\\2", $config_str);
	$config_str = preg_replace("/(icecast\['mount'\] = )\".+(\";)/", "\\1\"" . $_POST['ic_mount'] . "\\2", $config_str);
	$config_str = preg_replace("/(icecast\['admin_user'\] = )\".+(\";)/", "\\1\"" . $_POST['ic_admin_user'] . "\\2", $config_str);
	$config_str = preg_replace("/(icecast\['admin_pass'\] = )\".+(\";)/", "\\1\"" . $_POST['ic_admin_pass'] . "\\2", $config_str);
	$config_str = preg_replace("/(icecast\['ffmpeg'\] = )\".+(\";)/", "\\1\"" . $_POST['ic_ffmpeg'] . "\\2", $config_str);

	$config_str = preg_replace("/(sc_api_key = )\".+(\";)/", "\\1\"" . $_POST['sc_key'] . "\\2", $config_str);

	$newconfig_fh = fopen(dirname(dirname(__FILE__)) . "/config-working.php", "w");

	if ($newconfig_fh == FALSE) {
		header('HTTP/1.0 500 Internal Server Error');
		echo "Could not open/create config-working.php";
		die();
	}

	$ret = fwrite($newconfig_fh, $config_str);

	if ($ret == FALSE) {
		header('HTTP/1.0 500 Internal Server Error');
		echo "Could not write to config-working.php";
		die();
	}

	fclose($newconfig_fh);
	?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>MPD Interface setup</title>
</head>
<body>
<h1>MPD Interface setup</h1>
<hr />
<h2>Generated config.php</h2>
<?php echo nl2br( htmlspecialchars($config_str)); ?>
<hr />
<form action=<?php echo '"'.$here.'"'; ?> method="post">
<input type="hidden" name="setup" value="test">
<input type="submit" name="submit" value="Test the generated config">
<input type="submit" name="submit" value="Skip testing and create DB tables">
<input type="submit" name="submit" value="Skip testing and implement new config">
</form>
</body>
</html>
<?php
	exit;
} elseif ((!empty($_POST)) && ($_POST['setup'] == 'test')) {
	include dirname(__FILE__) . "/setup-test.php";
} elseif (empty($_POST) || empty($_POST['setup']) || (!isset($_POST['setup']))) {

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>MPD Interface setup</title>
</head>
<body>
<h1>MPD Interface setup</h1>
<hr />
<form action=<?php echo '"'.$here.'"'; ?> method="post">
<input type="hidden" name="setup" value="config">
<h2>General Settings</h2>
<div>MPD Interface Title:
<input type="text" name="title" value="MPD Interface"><br />
The name of this interface. You'll see it a lot.<br /><br /></div>
<div>MPD Interface ID String:
<input type="text" name="idstr" value="mpdinterface"><br />
This interface's ID string. For internal use. Not used yet. Alphanumeric characters only, please.<br /><br /></div>
<hr />
<h2>Session Settings</h2>
<div>Server-side session length (in seconds):
<input type="number" name="session_timeout" min="600" value="21600"><br />
This is how long PHP will keep a session in memory on the server.<br /><br /></div>
<div>Client-side (cookie) session length (in seconds):
<input type="number" name="cookie_timeout" min="0" value="21600"><br />
This is how long the cookies will remain on the client's machine. 0 means until the browser is closed.<br /><br /></div>
<hr />
<h2>MySQL</h2>
<div>Hostname/IP:
<input type="text" name="sql_host" value="localhost"><br /><br /></div>
<div>Port:
<input type="number" name="sql_port" min="0" max="65536" value="3306"><br /><br /></div>
<div>User:
<input type="text" name="sql_user" value="mpd_user"><br /><br /></div>
<div>Password:
<input type="password" name="sql_pass" value=""><br /><br /></div>
<div>Database name:
<input type="text" name="sql_db" value="mpd"><br /><br /></div>
<hr />
<h2>MPD</h2>
<div>Hostname/IP:
<input type="text" name="mpd_host" value="localhost"><br /><br /></div>
<div>Port:
<input type="number" name="mpd_port" min="0" max="65536" value="6600"><br /><br /></div>
<div>Password:
<input type="password" name="mpd_pass" value=""><br /><br /></div>
<hr />
<h2>Icecast</h2>
<div>Hostname/IP:
<input type="text" name="ic_host" value="localhost"><br /><br /></div>
<div>Port:
<input type="number" name="ic_port" min="0" max="65536" value="8000"><br /><br /></div>
<div>Stream Password:
<input type="password" name="ic_stream_pass" value=""><br />
Password used to stream to Icecast.<br /><br /></div>
<div>VBR Quality:
<input type="number" name="ic_qual" min="0" max="9" value="6"><br />
Quality (bitrate range) of the stream. Lower numbers are higher quality. See <a href="https://trac.ffmpeg.org/wiki/Encode/MP3#VBREncoding">here</a> for further explanation.<br /><br /></div>
<div>Mount:
<input type="text" name="ic_mount" value="stream.mp3"><br />
Where to put publicly accessuble stream.<br /><br /></div>
<div>Admin Username:
<input type="text" name="ic_admin_user" value="mpd_user"><br />
Username for accessing admin functions of Icecast.<br /><br /></div>
<div>Admin Password:
<input type="password" name="ic_admin_pass" value=""><br />
Password for accessing admin functions of Icecast.<br /><br /></div>
<div>Transcoder:
<input type="text" name="ic_ffmpeg" value="ffmpeg"><br />
Audio conversion utility for transcoding source tracks. Must be ffmpeg compatible (ex: ffmpeg, avconv). Must be in PATH or an absolute path.<br /><br /></div>
<hr />
<h2>Soundcloud</h2>
<div>API Key:
<input type="text" name="sc_key" value=""><br /><br /></div>
<input type="submit" name="submit" value="Submit">
</form>
<br /><br />The configuration can always be changed later in config.php. Debugging and logging settings can also be accessed here.
</body>
</html>
<?php } ?>