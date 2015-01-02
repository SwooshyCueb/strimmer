<?php
if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include dirname(dirname(__FILE__)) . "/functions.php";

$query = "SELECT * FROM db_cache WHERE PLAYING=1 LIMIT 1";
$result = mysqli_query($mysqli,$query);
$row = mysqli_fetch_array($result);

?>

<div class="col3_bg"><img src="cache/<?php echo $row['TRACKID']; ?>.jpg"/></div>
<div class="col_wrapper" id="col3_wrapper">
	<div class="selected_info_art"><img src="cache/<?php echo $row['TRACKID']; ?>.jpg"/></div>
	<div class="selected_info_title"><?php echo $row['RETURN_ARG2']; ?></div>
	<div class="selected_info_artist"><a href="<?php echo $row['RETURN_ARG4']; ?>"><?php echo $row['RETURN_ARG3']; ?></a></div>
</div>