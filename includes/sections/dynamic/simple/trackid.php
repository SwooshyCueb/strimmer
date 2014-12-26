<?php
// oh ffs
include dirname(dirname(dirname(dirname(__FILE__)))) . "/settings.php";
include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/session.php";
include dirname(dirname(dirname(dirname(__FILE__)))) . "/functions.php";

$query = "SELECT * FROM db_cache WHERE PLAYING=1 LIMIT 1";
$result = mysqli_query($mysqli,$query);
$row = mysqli_fetch_array($result);

echo $row['TRACKID'];

?>