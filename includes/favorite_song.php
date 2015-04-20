<?php
include_once dirname(__FILE__) . "/settings.php";
include_once dirname(__FILE__) . "/session.php";
include_once dirname(__FILE__) . "/functions.php";

if($_SESSION['login'] == FALSE) {
	header('HTTP/1.0 401 Unauthorized');
	die();
}

if(isset($_GET['ID'])) {
	$query = "SELECT * FROM db_cache WHERE TRACKID='" . $_GET['ID'] . "';";
	$storage = mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($storage);
	if(isset($storage)) {
		$query = 'SELECT FAVORITES FROM user_db WHERE ID=' . $_SESSION['user_id'];
		$result = mysqli_query($mysqli,$query);
		if(mysqli_num_rows($result) == 1) {
			$faves_sql = mysqli_fetch_array($result);
			$faves_str = $faves_sql['FAVORITES'];
			$faves_arr = explode(";",$faves_str);

			switch ($_GET['mode']) {
				case 'favorite':
					if(!in_array($row['TRACKID'],$faves_arr)) {
						$faves_str .= $row['TRACKID'] . ";";
						$query = 'UPDATE user_db SET FAVORITES="' . $faves_str . '" WHERE ID=' . $_SESSION['user_id'];
					}
					break;

				case 'unfavorite':
					if(in_array($row['TRACKID'],$faves_arr)) {
						$faves_str = str_replace($row['TRACKID'] . ";","",$faves_str);
						$query = 'UPDATE user_db SET FAVORITES="' . $faves_str . '" WHERE ID=' . $_SESSION['user_id'];
					}
					break;
				
				default:
					# code...
					break;
			}

			$result = mysqli_query($mysqli,$query);
		}
	}
}