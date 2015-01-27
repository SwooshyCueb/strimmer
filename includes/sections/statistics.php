<?php
if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include dirname(dirname(__FILE__)) . "/functions.php";

// worry about settings later
$query = "SELECT MIN(create_time) FROM INFORMATION_SCHEMA.TABLES WHERE table_schema='mpd'";
$result = mysqli_query($mysqli,$query);
$row = mysqli_fetch_array($result);

$library_creation_time = strtotime($row[0]);
$library_age['main'] = time() - $library_creation_time;
$library_age['seconds'] = $library_age['main'] % 60;
$library_age['minutes'] = floor($library_age['main'] / 60) % 60;
$library_age['hours'] = floor($library_age['main'] / 60 / 60) % 24;
$library_age['days'] = floor($library_age['main'] / 60 / 60 / 24);

$query = "SELECT COUNT(TRACKID),SUM(PLAY_COUNT) FROM db_cache";
$result = mysqli_query($mysqli,$query);
$row = mysqli_fetch_array($result);
$library_size = $row[0];
$tracks_played = $row[1];

$query = "SELECT COUNT(ID) FROM user_db";
$result = mysqli_query($mysqli,$query);
$row = mysqli_fetch_array($result);
$total_users = $row[0] - 1;
?>
<div class="col_wrapper" id="userlist_wrapper">
	<div style="margin: auto; width: 100%; line-height: 24px;">
		<div class="stats_section_wrapper">
			<div class="stats_section">
				<h1>Top Played Tracks</h1>
				<?php
					$query = "SELECT RETURN_ARG2,RETURN_ARG3,PLAY_COUNT FROM db_cache ORDER BY PLAY_COUNT DESC LIMIT 5";
					$result = mysqli_query($mysqli,$query);

					while($row = mysqli_fetch_array($result)) {
						if(!isset($max_plays)) {
							$max_plays = $row['PLAY_COUNT'];
						}
						echo $row['RETURN_ARG3'] . " - " . $row['RETURN_ARG2'];
						echo '<div class="graph_bar" style="width: calc(' . $row['PLAY_COUNT'] . '/' . $max_plays . ' * 100%)">';
							echo '<span class="graph_bar_caption">' . number_format($row['PLAY_COUNT']) . ' plays</span>';
						echo '</div>';
					}
				?>
			</div>
		</div>
		<div class="stats_section_wrapper">
			<div class="stats_section">
				<h1>Top User Contributions</h1>
				<?php
					$query = "SELECT ADDED_BY,COUNT(TRACKID) AS SUM_TRACKS FROM db_cache GROUP BY ADDED_BY ORDER BY SUM_TRACKS DESC LIMIT 5";
					$result = mysqli_query($mysqli,$query);

					while($row = mysqli_fetch_array($result)) {
						if(!isset($max_plays2)) {
							$max_plays2 = $row['SUM_TRACKS'];
						}
						echo $row['ADDED_BY'];
						echo '<div class="graph_bar" style="width: calc(' . $row['SUM_TRACKS'] . '/' . $max_plays2 . ' * 100%)">';
							echo '<span class="graph_bar_caption">' . number_format($row['SUM_TRACKS']) . ' tracks</span>';
						echo '</div>';
					}
				?>
			</div>
		</div>
		<div class="stats_section_wrapper">
			<div class="stats_section">
				<h1>Top Services</h1>
				<?php
					$query = "SELECT SERVICE,COUNT(TRACKID) AS SUM_TRACKS FROM db_cache GROUP BY SERVICE ORDER BY SUM_TRACKS DESC LIMIT 5";
					$result = mysqli_query($mysqli,$query);

					while($row = mysqli_fetch_array($result)) {
						if(!isset($max_plays3)) {
							$max_plays3 = $row['SUM_TRACKS'];
						}
						echo getLongService($row['SERVICE']);
						echo '<div class="graph_bar" style="width: calc(' . $row['SUM_TRACKS'] . '/' . $max_plays3 . ' * 100%)">';
							echo '<span class="graph_bar_caption">' . number_format($row['SUM_TRACKS']) . ' tracks</span>';
						echo '</div>';
					}
				?>
			</div>
		</div>
	</div>
	<div style="width: 100%; display: inline-block;">
		<div class="stats_section">
			<h1>General Statistics</h1>
			<table class="gen_stats">
				<tr>
					<td>Library Size</td>
					<td><?php echo number_format($library_size); ?> tracks</td>
				</tr>
				<tr>
					<td>Library Created On</td>
					<td><?php echo date('M. d, Y g:i A',$library_creation_time); ?></td>
				</tr>
				<tr>
					<td>Library Age</td>
					<td><?php echo $library_age['days'] . " days, " . $library_age['hours'] . " hours, " . $library_age['minutes'] . " minutes, " . $library_age['seconds'] . " seconds"; ?></td>
				</tr>
				<tr>
					<td>Total Users</td>
					<td><?php echo number_format($total_users); ?> users</td>
				</tr>
				<tr>
					<td>Tracks Played</td>
					<td><?php echo number_format($tracks_played); ?> tracks</td>
				</tr>
			</table>
		</div>
	</div>
</div>