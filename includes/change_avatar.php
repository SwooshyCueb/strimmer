<?php
	include_once dirname(__FILE__) . "/settings.php";
	include_once dirname(__FILE__) . "/session.php";

	if($_SESSION['login'] == FALSE) {
		header('HTTP/1.0 401 Unauthorized');
		die();
	}
	if($_SESSION['username'] != $_POST['username']) {
		header("Location: " . $_SERVER['HTTP_REFERER']);
		die();
	}
	$name = $_FILES['userfile']['name'];
	$tmpfile = $_FILES['userfile']['tmp_name'];
	$fname = dirname(dirname(__FILE__)) . "/locdata/images/avatars/" . $_POST['username'] . ".jpg";
	$image = new Imagick();
	$image->readImage($tmpfile);
	$image->setFormat("jpg");
	$image->setImageCompression(Imagick::COMPRESSION_JPEG);
	$image->setImageCompressionQuality(100);
	$image->thumbnailImage(100,100);
	$image->writeImage($fname);
	$image->clear();
	header("Location: " . $_SERVER['HTTP_REFERER']);
	die();
?>