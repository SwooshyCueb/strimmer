<?php

include dirname(__FILE__) . "/settings.php";

function mpd_getPlaylist() {
	$playlist = explode("\n",str_replace("\n\r","\n",shell_exec("mpc -f \"%file%\" -h " . $mpd['host'] . " -p " . $mpd['port'] . " playlist")));
	return $playlist;
}

function getLongService($service) {
	switch ($service) {
		case 'SDCL':
			return "SoundCloud";
			break;

		case 'WYZL':
			return "Weasyl";
			break;

		case 'JMND':
			return "Jamendo";
			break;
		
		default:
			return "N/A";
			break;
	}
}

function validateStream($url) {
	$ffprobeoutarr = [];
	exec($icecast['ffprobe'] . ' -hide_banner -i \'' . $url . '\'', $ffprobeout);
	$ffprobeout = implode("\n", $ffprobeoutarr);
	$audiostreams = [];
	$valid = preg_match_all("/Stream #[0-9]+:[0-9]+: Audio: (.+), ([0-9]+ Hz), .+ ([0-9]+ kb\/s)/", $ffprobeout, $audiostreams);
	if ($valid) {
		return True;
	} else {
		return False;
	}
}

//
// --= ALL LIST ROWS WILL BE DRAWN BY THIS FUNCTION =--
//
function getListRow_Service($row,$page,$additional_data) {

	if(!isset($page)) {
		echo "Error in request, no page specified.";
		die();
	}

	// apparently this php script is called before /css/main.php?
	include dirname(dirname(__FILE__)) . "/css/themes/" . $_SESSION['theme'] . ".php";

	$filename = dirname(dirname(__FILE__)) . "/cache" . "/" . $row['TRACKID'] . ".jpg";
	if(!file_exists($filename)) {
		if (!file_exists(dirname(dirname(__FILE__)) . "/cache"))
		{
    		mkdir(dirname(dirname(__FILE__)) . "/cache", 0765, true);
    		// For whatever reason, imagemagick can't write images to this folder
    		// if we don't have exec perms
		}
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
	//<span class="oi" data-glyph="pulse"></span>

	if(!isset($row['SERVICE'])) {
		echo "Error in request, no service specified.";
		die();
	}

	if($row['PLAYING'] == 1) {
		$classes = "song_row playing";
	} else {
		$classes = "song_row";
	}

	echo '<tr class="' . $classes . '" id="' . $row['TRACKID'] . '">';

		switch($page) {
			case "default":
				$col2_text = $row['ADDED_BY'];
				$col3_text = date('M. d, Y g:i A',$row['ADDED_ON']);
				break;

			case "queue":
				if(!isset($additional_data['ADDED_BY'])) {
					//this is blank for whatever reason??
					//$added_by = $prog_title;
					$col2_text = "Strimmer";
				} else {
					$col2_text = $additional_data['ADDED_BY'];
				}
				$col3_text = date('M. d, Y g:i A',$additional_data['ADDED_ON']);
				break;

			case "history":
				if(!isset($additional_data['ADDED_BY'])) {
					//this is blank for whatever reason??
					//$added_by = $prog_title;
					$col2_text = "Strimmer";
				} else {
					$col2_text = $additional_data['ADDED_BY'];
				}
				$col3_text = date('M. d, Y g:i A',$additional_data['PLAYED_ON']);
				break;
		}

		?>
		<td>
			<img src="cache/<?php echo $row['TRACKID']; ?>.jpg" class="list_art"/>
			<div class="list_info">
				<div class="overflow_grd"></div>
				<?php
					if ($_SESSION['login']) {
						if($row['ADDED_ON'] + 1 >= $_SESSION['LASTACTIVE']) {
							echo '<span class="balloon" style="background-color: ' . $balloon_color['new'] . '; color: ' . $balloon_color['font_new'] . ';">NEW</span>';
						}
						// it's there when we add it, if ever
						/* if(isset($row['ERROR'])) {
							echo '<span class="balloon" style="background-color: ' . $balloon_color['error'] . '; color: ' . $balloon_color['font_error'] . ';">ERROR</span>';
						} */
					}
				?>
				<div class="list_title" style="z-index: 0;"><?php echo $row['RETURN_ARG2']; ?></div>
				<div class="list_artist" style="z-index: 0;"><a href="<?php echo $row['RETURN_ARG4']; ?>"><?php echo $row['RETURN_ARG3']; ?></a></div>
			</div>
		</td>
		<td><?php echo $col2_text; ?></td>
		<td><?php echo $col3_text; ?></td>
		<td><img src="assets/services/<?php echo $row['SERVICE']; ?>.png" class="list_svc"></td>

	</tr>
<?php
}
?>