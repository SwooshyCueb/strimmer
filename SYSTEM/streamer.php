<?php
	include_once dirname(dirname(__FILE__)) . "/includes/settings.php";
	$time = 0;
	$previous_song = "";

	while(true) {
		//emergency end
		if(time() - $time <= 10) {
			echo "EMERGENCY END";
			break;
		}

		$query = "SELECT * FROM db_cache ORDER BY RAND() LIMIT 1";
		$result = mysqli_query($mysqli,$query);

		if(!isset($result)) {
			echo "NO SQL RESULT GIVEN";
			break;
		}
		
		$row = mysqli_fetch_array($result);

		// temporary fix to the repetition issue
		// this'll have to be reworked when we add in the play queue
		while($row['TRACKID'] == $previous_song) {
			$result = mysqli_query($mysqli,$query);
			$row = mysqli_fetch_array($result);
		}
		$previous_song = $row['TRACKID'];

		$url_str = $row['RETURN_ARG3'] . " - " . $row['RETURN_ARG2'];
		echo $url_str;

		putenv("ICHOST=" . $icecast['host']);
		putenv("ICPORT=" . $icecast['port']);
		putenv("ICMOUNT=" . $icecast['mount']);
		putenv("ICADMIN_USER=" . $icecast['admin_user']);
		putenv("ICADMIN_PASS=" . $icecast['admin_pass']);

		// anything i'm trying to do involving escaping flat out fails, so i caved and i'm doing this -.-
		$cmd_str = str_replace('\\', '\\\\', $url_str);
		$cmd_str = str_replace('$', '\$', $cmd_str);
		$cmd_str = str_replace('"', '\"', $cmd_str);
		exec('./metadata_upd "' . $cmd_str . '" > metadata_log 2>&1 &');

		switch ($row['SERVICE']) {
			case 'SDCL':
				$stream_link = $row['RETURN_ARG5'] . "?client_id=" . $sc_api_key;
				break;

			case 'WYZL':
				$stream_link = $row['RETURN_ARG5'];
				break;
			default:
				# code...
				break;
		}

		$time = time();

		exec($icecast['ffmpeg'] . ' -re -i \'' . $stream_link . '\' -acodec libmp3lame -q ' . $icecast['qual'] . ' -content_type "audio/mpeg3" -metadata title="' . $cmd_str . '" "icecast://source:' . $icecast['pass'] . '@' . $icecast['host'] . ':' . $icecast['port'] . '/' . $icecast['mount'] . '"');

	}
?>
