<?php
	include_once dirname(__FILE__) . "/settings.php";
	include_once dirname(__FILE__) . "/session.php";

	if ($_SESSION['login'] == FALSE) {
		header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "login/");
		die();
	}

	if(isset($_POST['timezone'])) {
		$query = 'UPDATE user_db SET TIMEZONE="' . htmlspecialchars($_POST['timezone']) . '" WHERE USERNAME="' . $_SESSION['username'] . '"';
		$result = mysqli_query($mysqli,$query);
	}

	if(isset($_POST['theme'])) {
		$query = 'UPDATE user_db SET THEME="' . htmlspecialchars($_POST['theme']) . '" WHERE USERNAME="' . $_SESSION['username'] . '"';
		$result = mysqli_query($mysqli,$query);
	}

	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit;