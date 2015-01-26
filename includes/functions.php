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

		case 'UNDF':
			return "None";
			break;
		
		default:
			return "N/A";
			break;
	}
}

function getUserAvatarFilename($username)
{
	$avvy_loc = dirname(dirname(__FILE__)) . '/locdata/images/avatars/';
	if (!file_exists($avvy_loc . $username . ".jpg"))
	{
		return "guest.jpg";
	} else {
		return $username . ".jpg";
	}
}

function getUserAvatarFile($username)
{
	$avvy_loc = dirname(dirname(__FILE__)) . '/locdata/images/avatars/';
	return $avvy_loc . getUserAvatarFilename($username);
}

function placeholderAlbumArt($trackID, $added_by)
{
	copy(getUserAvatarFile($added_by), dirname(dirname(__FILE__)) . "/cache" . "/" . $trackID . ".jpg");
}

//
// Stream information functions
// At the moment, these are only used in the plain backend.
// They could be useful in other backehnds, however, so we're keeping them in the global functions file.
//
function getStreamInfo($url) {
	include dirname(__FILE__) . "/settings.php";
	$ffprobeoutarr = [];
	exec($icecast['ffprobe'] . ' -hide_banner -show_streams \'' . $url . '\'', $ffprobeoutarr);
	$streams = [];
	$streamidx = 0;
	$instreamblock = False;
	foreach ($ffprobeoutarr as $line)
	{
		if ($instreamblock)
		{
			if ($line === "[/STREAM]")
			{
				$streamidx = $streamidx + 1;
				$instreamblock = False;
			} else {
				$data = explode("=", $line, 2);
				if (strpos($data[0], ':') !== FALSE)
				{
					$data0 = explode(":", $data[0], 2);
					$streams[$streamidx][$data0[0]][$data0[1]] = $data[1];
				} else {
					$streams[$streamidx][$data[0]] = $data[1];
				}
			}
		} else {
			if ($line === "[STREAM]")
			{
				$instreamblock = True;
			}
		}
	}
	if ($streamidx == 0)
	{
		return False;
	} else {
		return $streams;
	}
}

function validateStream($streaminfo) {
	$validstreams = 0;
	foreach ($streaminfo as $stream)
	{
		if ($stream["codec_type"] === "audio")
		{
			$validstreams = $validstreams + 1;
		}
	}
	if ($validstreams == 0) {
		return False;
	} else {
		return True;
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
		if ($row['RETURN_ARG7'])
		{
			$image = new Imagick();
			$image->readImage($row['RETURN_ARG7']);
			$image->setFormat("jpg");
			$image->setImageCompression(Imagick::COMPRESSION_JPEG);
			$image->setImageCompressionQuality(97);
			$image->thumbnailImage(100,100);
			$image->writeImage($filename);
			$image->clear();
		} else {
			if ($row['SERVICE'] === "UNDF")
			{
				include dirname(__FILE__) . "/backends/plain-func.php";
				if (!dumpAlbumArt($row['RETURN_ARG5'], $row['TRACKID']))
				{
					placeholderAlbumArt($row['TRACKID'], $row['ADDED_BY']);
				}		
			} else {
				placeholderAlbumArt($row['TRACKID'], $row['ADDED_BY']);
			}
		}
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