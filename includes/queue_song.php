<?php
include_once dirname(__FILE__) . "/settings.php";
include_once dirname(__FILE__) . "/session.php";
include_once dirname(__FILE__) . "/functions.php";

if($_SESSION['login'] == FALSE) {
	header('HTTP/1.0 401 Unauthorized');
	die();
}

switch ($_GET['mode']) {
	case 'queue':
		if(isset($_GET['ID'])) {
			// this whole section needs to be optimized, lol
			$query = "SELECT * FROM db_cache WHERE TRACKID='" . $_GET['ID'] . "';";
			$storage = mysqli_query($mysqli,$query);
			$row = mysqli_fetch_array($storage);
			if(isset($storage)) {
				$query = 'INSERT INTO play_queue ( TRACKID, SERVICE, ADDED_ON, ADDED_BY ) VALUES ( "' . $row['TRACKID'] . '", "' . $row['SERVICE'] . '", ' . time() . ', "' . $_SESSION['username'] . '")';
				$result = mysqli_query($mysqli,$query);
			}
		}
		// header("Location: " . $_SERVER['HTTP_REFERER']);
		break;

	case 'unqueue':
		if(isset($_GET['ID'])) {
			$query = 'SELECT * FROM play_queue WHERE TRACKID="' . $_GET['ID'] . '" AND !ISNULL(play_queue.ADDED_BY) LIMIT 1';
			$result = mysqli_query($mysqli,$query);
			if(mysqli_num_rows($result)) {
				$query = 'DELETE FROM play_queue WHERE TRACKID="' . $_GET['ID'] . '" AND !ISNULL(play_queue.ADDED_BY) LIMIT 1';
				$result = mysqli_query($mysqli,$query);
			}
		}
		// header("Location: " . $_SERVER['HTTP_REFERER']);
		break;
	
	default:
		# code...
		break;
}