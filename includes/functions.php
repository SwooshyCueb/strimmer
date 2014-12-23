<?php

include dirname(__FILE__) . "/settings.php";

function mpd_getPlaylist() {
	$playlist = explode("\n",str_replace("\n\r","\n",shell_exec("mpc -f \"%file%\" -h " . $mpd['host'] . " -p " . $mpd['port'] . " playlist")));
	return $playlist;
}

//
// --= ALL LIST ROWS WILL BE DRAWN BY THIS FUNCTION =--
//
function getListRow_Service($row) {

	// apparently this php script is called before /css/main.php?
	include dirname(dirname(__FILE__)) . "/css/themes/default.php";

	$filename = dirname(dirname(__FILE__)) . "/cache" . "/" . $row['TRACKID'] . ".jpg";
	if(!file_exists($filename)) {
		$image = new Imagick();
		$image->readImage($row['RETURN_ARG7']);
		$image->setFormat("jpg");
		$image->setImageCompression(Imagick::COMPRESSION_JPEG);
		$image->setImageCompressionQuality(97);
		$image->thumbnailImage(100,100);
		$image->writeImage($filename);
		$image->clear();
	}
	// sublime is saying /cache/ is escaped, so it's separated in case
	// unsure if it /actually is/ but w/e
	
	switch ($row['SERVICE']) {
		case 'SDCL':
			echo '<tr class="song_row">';
				echo '<td>';
					echo '<img src="cache/' . $row['TRACKID'] . '.jpg" class="list_art"/>';
					echo '<div class="list_info">';
						echo '<div class="overflow_grd"></div>';
						if ($_SESSION['login']) {
							echo '<a href="includes/delete_song.php?id=' . $row['TRACKID'] . '&user=' . $_SESSION['username'] . '"/><span class="oi" data-glyph="delete" id="list_delete"></span></a>';
							// uncomment once LASTACTIVE is tracked again
							/* if($row['ADDED_ON'] >= $_SESSION['LASTACTIVE']) {
								echo '<span class="balloon" style="background-color: ' . $balloon_color['new'] . '; color: ' . $balloon_color['font_new'] . ';">NEW</span>';
							} /*

							// it's there when we add it, if ever
							/* if(isset($row['ERROR'])) {
								echo '<span class="balloon" style="background-color: ' . $balloon_color['error'] . '; color: ' . $balloon_color['font_error'] . ';">ERROR</span>';
							} */
						}
						echo '<div class="list_title" style="z-index: 0;">' . $row['RETURN_ARG2'] . '</div>';
						echo '<div class="list_artist" style="z-index: 0;"><a href="' . $row['RETURN_ARG4'] . '">' . $row['RETURN_ARG3'] . '</a></div>';
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
					echo '<img src="cache/' . $row['TRACKID'] . '.jpg" class="list_art"/>';
					echo '<div class="list_info">';
						echo '<div class="overflow_grd"></div>';
						if ($_SESSION['login']) {
							echo '<a href="includes/delete_song.php?id=' . $row['TRACKID'] . '&user=' . $_SESSION['username'] . '"/><span class="oi" data-glyph="delete" id="list_delete"></span></a>';
						}
						echo '<div class="list_title" style="z-index: 0;">' . $row['RETURN_ARG2'] . '</div>';
						echo '<div class="list_artist" style="z-index: 0;"><a href="' . $row['RETURN_ARG4'] . '">' . $row['RETURN_ARG3'] . '</a></div>';
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