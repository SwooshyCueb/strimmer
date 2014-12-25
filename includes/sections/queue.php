<?php
if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include dirname(dirname(__FILE__)) . "/functions.php";

$query = "SELECT * FROM play_queue";
$result_queue = mysqli_query($mysqli,$query);

?>
<div class="col_wrapper">
	<table class="song_list">
			<tr class="h_row">
				<td>Up next</td>
				<td>Added by</td>
				<td>Added on</td>
				<td></td>
			</tr>
			<?php
			while($row = mysqli_fetch_array($result_queue)) {
				$query = 'SELECT * FROM db_cache WHERE TRACKID="' . $row['TRACKID'] . '"';
				$result = mysqli_query($mysqli,$query);
				if(mysqli_num_rows($result) > 0) {
					getListRow_Service(mysqli_fetch_array($result));
				}
			}
		?>
	</table>
</div>