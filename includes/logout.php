<?php
	include_once dirname(__FILE__) . "/settings.php";

	session_start();
	if(session_destroy()) {
		header("Location: " . $_SERVER['HTTP_REFERER']);
		die();
	}
?>