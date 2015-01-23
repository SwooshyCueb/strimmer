<?php
if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include dirname(dirname(dirname(__FILE__))) . "/settings.php";
include_once dirname(dirname(dirname(__FILE__))) . "/session.php";
include dirname(dirname(dirname(__FILE__))) . "/functions.php";

/*if(isset($_GET['user'])) {
	$query = 'SELECT * FROM db_cache WHERE ADDED_BY="' . $_GET['user'] . '" ORDER BY ADDED_ON';
} else {
	$query = "SELECT * FROM db_cache ORDER BY ADDED_ON;";
}*/
$query = "SELECT COUNT('TRACKID') FROM db_cache";
$result = mysqli_query($mysqli,$query);
$temp = mysqli_fetch_array($result);
echo $temp[0] . " " . $_SESSION['oldcount'];

if(!isset($_SESSION['oldcount'])) {
	$_SESSION['oldcount'] = $temp[0];
}

$diff = $temp[0] - $_SESSION['oldcount'];
if($diff <= 0) {
	$query = "SELECT * FROM db_cache ORDER BY ADDED_ON DESC LIMIT " . $diff;
	$result = mysqli_query($mysqli,$query);

	if(mysqli_num_rows($result)) {
		while($row = mysqli_fetch_array($result)) {
			getListRow_Service($row);
		}
	}
}

$_SESSION['oldcount'] = $temp[0];

?>