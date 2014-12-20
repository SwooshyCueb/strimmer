<?php
	include_once dirname(__FILE__) . "/settings.php";

	if(isset($_SESSION['username'])) {
		header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		die();
	}

	$username = mysqli_real_escape_string($mysqli,stripslashes(htmlspecialchars($_POST['username'])));
	$password = mysqli_real_escape_string($mysqli,stripslashes(htmlspecialchars($_POST['password'])));
	if(strlen($username) >= 4 && strlen($username) < 64) {
		$query = 'SELECT * FROM user_db WHERE USERNAME="' . $username . '"';
		$result = mysqli_query($mysqli,$query);
		if(mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_array($result);
			if($row['PASSWORD'] == hash("sha512",$password . "-:-" . $username)) {
				session_start();
				$_SESSION['login'] = "1";
				$_SESSION['username'] = $username;
				$_SESSION['user_id'] = $row['ID'];
				header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
				exit;
			}
			else {
				header("Location: " . $_SERVER['HTTP_REFERER']);
				exit;
			}
		}
		else {
			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit;
		}
	}
	else {
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	}