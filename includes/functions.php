<?php

include_once dirname(__FILE__) . "/settings.php";

function mpd_getPlaylist() {
	$playlist = explode("\n",str_replace("\n\r","\n",shell_exec("mpc -f \"%file%\" -h " . $mpd['host'] . " -p " . $mpd['port'] . " playlist")));
	return $playlist;
}

//
// --= ALL LIST ROWS WILL BE DRAWN BY THIS FUNCTION =--
//
function getListRow_Service($row) {
	switch ($row['SERVICE']) {
		case 'SDCL':
			echo '<tr class="song_row">';
				echo '<td>';
					echo '<img src="' . $row['RETURN_ARG7'] . '" class="list_art"/>';
					echo '<div class="list_info">';
						if ($_SESSION['login']) {
							echo '<a href="includes/delete_song.php?id=' . $row['TRACKID'] . '&user=' . $_SESSION['username'] . '"/><span class="oi" data-glyph="delete" id="list_delete"></span></a>';
						}
						echo '<span class="list_title">' . $row['RETURN_ARG2'] . '</span><br/>';
						echo '<span class="list_artist"><a href="' . $row['RETURN_ARG4'] . '">' . $row['RETURN_ARG3'] . '</a></span>';
					echo '</div>';
				echo '</td>';
				echo '<td>' . $row['ADDED_BY'] . '</td>';
				echo '<td>' . date('M. d, Y g:i A',$row['ADDED_ON']) . '</td>';
				echo '<td><img src="assets/services/soundcloud.png" class="list_svc"></td>';
			echo '</tr>';
			break;

		case 'WYZL':
			echo '<tr class="song_row">';
				echo '<td>';
					echo '<img src="' . $row['RETURN_ARG7'] . '" class="list_art"/>';
					echo '<div class="list_info">';
						if ($_SESSION['login']) {
							echo '<a href="includes/delete_song.php?id=' . $row['TRACKID'] . '&user=' . $_SESSION['username'] . '"/><span class="oi" data-glyph="delete" id="list_delete"></span></a>';
						}
						echo '<span class="list_title">' . $row['RETURN_ARG2'] . '</span><br/>';
						echo '<span class="list_artist"><a href="' . $row['RETURN_ARG4'] . '">' . $row['RETURN_ARG3'] . '</a></span>';
					echo '</div>';
				echo '</td>';
				echo '<td>' . $row['ADDED_BY'] . '</td>';
				echo '<td>' . date('M. d, Y g:i A',$row['ADDED_ON']) . '</td>';
				echo '<td><img src="assets/services/weasyl.png" class="list_svc"></td>';
			echo '</tr>';
			break;

		default:
			break;
	}
}