<?php
if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include dirname(dirname(dirname(__FILE__))) . "/settings.php";
include_once dirname(dirname(dirname(__FILE__))) . "/session.php";
include dirname(dirname(dirname(__FILE__))) . "/functions.php";

$query = "SELECT * FROM db_cache WHERE PLAYING=1 LIMIT 1";
$result = mysqli_query($mysqli,$query);
$row = mysqli_fetch_array($result);

?>

<img src="cache/<?php echo $row['TRACKID']; ?>.jpg" class="np_art"/>
<span class="np_title"><?php echo $row['RETURN_ARG2']; ?></span><br/>
<span class="np_artist"><a href="<?php echo $row['RETURN_ARG4']; ?>"><?php echo $row['RETURN_ARG3']; ?></a></span><br/>
<span class="np_who">Added by <?php echo $row['ADDED_BY']; ?></span>