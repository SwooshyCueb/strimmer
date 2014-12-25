<?php
if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include dirname(dirname(__FILE__)) . "/functions.php";

if(isset($_GET['user'])) {
	$query = 'SELECT * FROM db_cache WHERE ADDED_BY="' . $_GET['user'] . '" ORDER BY ADDED_ON';
} else {
	$query = "SELECT * FROM db_cache ORDER BY ADDED_ON;";
}

$result = mysqli_query($mysqli,$query);

$user_nonexistant = 0;
if(!mysqli_num_rows($result)) {
	$user_nonexistant = 1;
}
?>
<div class="col_wrapper">
	<table class="song_list">
		<?php
			if(!$user_nonexistant) {
			?>
				<tr class="h_row">
					<td>Song</td>
					<td>Added by</td>
					<td>Added on</td>
					<td></td>
				</tr>
			<?php
				while($row = mysqli_fetch_array($result)) {
					getListRow_Service($row);
				}
			} else {
				echo "<p>Specified user " . $_GET['user'] . " does not exist.</p>";
			}
		?>
	</table>
</div>