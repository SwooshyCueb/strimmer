<?php
	include_once dirname(__FILE__) . "/settings.php";

	session_start();

	if(!isset($_SESSION['login'])) {
		$_SESSION['login'] = FALSE;
		$_SESSION['username'] = "guest";
	}
	$query = 'SELECT USERNAME,TIMEZONE,ID,THEME FROM user_db WHERE USERNAME="' . $_SESSION['username'] . '"';
	$result = mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($result);
	if(!isset($row['USERNAME'])) {
		session_destroy();
	} else {
		if ($row['USERNAME'] == "guest") {
			$_SESSION['user_id'] = $row['ID'];
		} else {
			$query = 'UPDATE user_db SET LASTACTIVE=' . time() . ' WHERE USERNAME="' . $row['USERNAME'] . '"';
			$_SESSION['LASTACTIVE'] = time();
			$_SESSION['theme'] = $row['THEME'];
			$result = mysqli_query($mysqli,$query);
		}
		setcookie(session_name(),session_id(),time()+86400);
		date_default_timezone_set($row['TIMEZONE']);
	}
?>