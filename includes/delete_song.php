<?php
include_once dirname(__FILE__) . "/settings.php";
include_once dirname(__FILE__) . "/session.php";
include_once dirname(__FILE__) . "/functions.php";

if($_SESSION['login'] == FALSE) {
	header('HTTP/1.0 401 Unauthorized');
	die();
}

if(isset($_GET['id'])) {
	$query = "SELECT * FROM db_cache WHERE TRACKID='" . $_GET['id'] . "';";
	$storage = mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($storage);
	if(isset($storage)) {
		$query = "DELETE FROM db WHERE TRACKID='" . $_GET['id'] . "';";
		$result = mysqli_query($mysqli,$query);
		if(isset($result)) {
			$query = "DELETE FROM db_cache WHERE TRACKID='" . $_GET['id'] . "';";
			$result = mysqli_query($mysqli,$query);
			$playlist = mpd_getPlaylist();
			foreach ($playlist as $key => $song) {
				$pos = $key+1;
				if(isset($song)) {
					if(strpos($song, $row['RETURN_ARG5']) !== FALSE) {
						exec("mpc -h " . $mpd['password'] . "@" . $mpd['host'] . " -p " . $mpd['port'] . " del " . $pos);
						break;
					}
				}
			}
		}
	}
}