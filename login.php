<?php

include_once "includes/settings.php";

session_start();

$invalid_cred = FALSE;

if (!isset($_SESSION['mpdidx'])) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
} elseif (isset($_SESSION['username'])) {
	header("Location: http://" . $_SESSION['loginrefer']);
	die();
} elseif (empty($_SERVER['HTTP_REFERER']) || ((stripos($_server['HTTP_REFERER'], 'login.php') !== FALSE ) && isset($_SESSION['loginrefer']))) {
	$_SESSION['loginrefer'] = $_SESSION['mpdidx'];
} elseif (!isset($_SESSION['loginrefer'])) {
	$_SESSION['loginrefer'] = $_SERVER['HTTP_REFERER'];
}

if (!empty($_POST)) {
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
				header("Location: ". $_SESSION['loginrefer']);
				exit;
			} else {
				$invalid_cred = TRUE;
			}
		} else {
			$invalid_cred = TRUE;
		}
	} else {
		$invalid_cred = TRUE;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<strong>Please login to access the MPD interface</strong>
<form action="login.php" method="post">
	Username<br/>
	<div><input type="text" name="username" style="width: 256px;" placeholder="Username" required></div><br/>
	
	Password<br/>
	<div><input type="password" name="password" style="width: 256px;" placeholder="Password" required></div><br/>
	
	<input type="submit" value="Login" class="button"/>
</form>
<?php if ($invalid_cred) { ?>
<span style="color: rgb(255,0,0); font-weight: bold;">Invalid username or password.</span>
<?php } ?>
</body>
</html>