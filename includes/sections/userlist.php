<?php
if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include dirname(dirname(__FILE__)) . "/functions.php";

$query = "SELECT USERNAME FROM user_db ORDER BY USERNAME ASC";
$result = mysqli_query($mysqli,$query);

?>
<div class="col_wrapper" id="userlist_wrapper">
	<?php
		while ($row = mysqli_fetch_array($result)) {
			if($row['USERNAME'] == "guest") {
				continue;
			}
			echo '<div class="userdiv" id="' . $row['USERNAME'] . '">';
				echo '<img class="userdiv_av" src="locdata/images/avatars/' . $row['USERNAME'] . '.jpg"/>';
				echo '<div class="userdiv_name">' . $row['USERNAME'] . '</div>';
			echo '</div>';
		}
	?>
</div>