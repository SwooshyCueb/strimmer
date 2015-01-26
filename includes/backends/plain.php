<?php
include dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include_once dirname(dirname(__FILE__)) . "/functions.php";
include dirname(__FILE__) . "/plain-func.php";

if($_SESSION['login'] == FALSE) {
	header('HTTP/1.0 401 Unauthorized');
	die();
}

function getDialog($msg,$buttons) {
	echo $msg;
	echo '<div class="dialog_buttons">';
		foreach ($buttons as $button) {
			switch ($button) {
				case 'ok':
					echo '<span class="button" id="close_button_dg">OK</span>';
					break;

				case 'add':
					echo '<span class="button" id="retry_button_dg" onClick="button_retry();">Add another</span>';
					break;
				
				default:
					echo '<span class="button" id="close_button_dg">Cancel</span>';
					break;
			}
		}
	echo '</div>';
}

function getInfoDialog($title, $artist, $ID) {
	?>
	<form name='info-dl-form' id='info-dl-form'>
		<div style="width: 700px;">
			<div style="text-align: left;">Please provide some information about this track.</div><br />
			<div style="width: 500px; float: left;">
				<div style="text-align: left;">Title:</div>
				<div><input class="plain_title_str" type="text" name="plain_title_str" style="width: 500px;" value="<?php echo htmlspecialchars($title, ENT_COMPAT); ?>" required><br/></div>
				<div style="text-align: left;">Artist:</div>
				<div><input class="plain_artist_str" type="text" name="plain_artist_str" style="width: 500px;" value="<?php echo htmlspecialchars($artist, ENT_COMPAT); ?>" required><br/></div>
			</div>
			<div style="width: 200px; float: right; text-align: center;">
				<img src="cache/<?php echo $ID; ?>.jpg" style="margin: 5px;"><br />
				<input class="dl_art" type="file" name="dl_art">
			</div>
			<input type="hidden" name="mode" value="info">
			<input class="dl_url" type="hidden" name="dl_url" value="<?php echo htmlspecialchars($_POST['dl_url'], ENT_COMPAT); ?>">
			<input class="dl_id" type="hidden" name="dl_id" value="<?php echo $ID; ?>">
			<div class="dialog_buttons" style="display: inline-block;">
				<span class="button" onClick="submitInfo();">OK</span>
				<span class="button" id="close_button_dg">Cancel</span>
			</div>
		</div>
	</form>
	<?php
}

if(isset($_POST['mode'])) {
	if(isset($_POST['dl_url'])) {
		switch ($_POST['mode']) {
			case 'add':
				$stream_data = getStreamInfo($_POST['dl_url']);
				if (!validateStream($stream_data))
				{
					$msg = "The URL you provided does not appear to be streamable: " . $_POST['sc_url'] . ".";
					$buttons[1] = "ok";
					$buttons[2] = "add";
					echo(getDialog($msg,$buttons));
					exit;
				}
				$stream_data = getFirstPlayableStream($stream_data);
				$format_data = getFormatInfo($_POST['dl_url']);
				$dl_trk_id = generateID($format_data);

				if(!dumpAlbumArt($_POST['dl_url'], $dl_trk_id))
				{
					placeholderAlbumArt($dl_trk_id, $_SESSION['username']);
				}

				$dl_trk_title = getTitle($format_data);
				if(!$dl_trk_title)
				{
					$dl_trk_title = basename($_POST['dl_url']);
				}
				$dl_trk_artist = getArtist($format_data);
				if(!$dl_trk_artist)
				{
					$dl_trk_artist = "Unknown Artist";
				}
				getInfoDialog($dl_trk_title, $dl_trk_artist, $dl_trk_id);
				exit;
				break;
			case 'info':
				$time = time();
				$stream_data = getStreamInfo($_POST['dl_url']);
				$stream_data = getFirstPlayableStream($stream_data);
				$format_data = getFormatInfo($_POST['dl_url']);
				$query = 'INSERT INTO db ( TRACKID,SERVICE,SERVICE_ARG1,ADDED_BY,ADDED_ON ) VALUES (
					"' . $_POST['dl_id'] . '",
					"UNDF",
					"' . $_POST['dl_url'] . '",
					"' . $_SESSION['username'] . '",
					' . $time . '
					)';
				if($result) {
					/* 
						blank   	RETURN_ARG1
						title		RETURN_ARG2
						artist  	RETURN_ARG3
						"#"     	RETURN_ARG4
						stream url	RETURN_ARG5
						permalink	RETURN_ARG6
						blank   	RETURN_ARG7
					*/

					$query = 'INSERT INTO db_cache ( TRACKID,SERVICE,RETURN_ARG2,RETURN_ARG3,RETURN_ARG4,RETURN_ARG5,RETURN_ARG6,ADDED_BY,ADDED_ON ) VALUES (
						"' . $_POST['dl_id'] . '",
						"UNDF",
						"' . $_POST['plain_title_str'] . '",
						"' . $_POST['plain_artist_str'] . '",
						"#",
						"' . $_POST['dl_url'] . '",
						"' . $_POST['dl_url'] . '",
						"' . $_SESSION['username'] . '",
						' . $time . '
						)';
					$result = mysqli_query($mysqli,$query);

					$msg = "Your track has been successfully added.";
					$buttons[1] = "ok";
					$buttons[2] = "add";
					echo(getDialog($msg,$buttons));
					exit;
				} else {
					// TODO: Move this to the add section
					$query = 'SELECT TRACKID FROM db_cache WHERE TRACKID="' . $_POST['dl_id'] . '" LIMIT 1';
					$result = mysqli_query($mysqli,$query);
					$row = mysqli_fetch_array($result);
					
					if(isset($row['TRACKID'])) {
						$msg = "This track already exists in the library.";
						$buttons[1] = "ok";
						$buttons[2] = "add";
						echo(getDialog($msg,$buttons));
						exit;
					}
				}

				break;
			
			default:
				break;
		}
	}
}