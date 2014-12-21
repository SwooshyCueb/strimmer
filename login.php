<?php

session_start();

if (!isset($_SESSION['mpdidx'])) {?>
	<strong>Error</strong>: Premature navigation to login page.
	Please go to the MPD interface home page before trying to login.
	<?php
	exit;
}

if (empty($_SERVER['HTTP_REFERER']) || ((stripos($_server['HTTP_REFERER'], 'login.php') !== FALSE ) && isset($_SESSION['loginrefer']))) {
	$_SESSION['loginrefer'] = $_SESSION['mpdidx'];
} elseif (isset($_SESSION['loginrefer'])) {
	$_SESSION['loginrefer'] = $_SERVER['HTTP_REFERER'];
}
?>

<strong>Please login to access the MPD interface</strong>
<form action="includes/login.php" method="post">
	Username<br/>
	<div><input type="text" name="username" style="width: 256px;" placeholder="Username" required></div><br/>
	
	Password<br/>
	<div><input type="password" name="password" style="width: 256px;" placeholder="Password" required></div><br/>
	
	<input type="submit" value="Login" class="button"/>
</form>