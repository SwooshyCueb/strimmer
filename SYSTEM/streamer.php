<?php
	include_once dirname(dirname(__FILE__)) . "/includes/settings.php";
	include_once dirname(dirname(__FILE__)) . "/config.php";

	$time = 0;
	$previous_song = "";

	while(true) {
		//emergency end
		if(time() - $time <= 10) {
			sleep(15);
		}

		/* OLD STREAMER CODE, KEEPING IN CASE OF THINGS
		// PRE-PLAY-QUEUE UPDATE
		// seems a tad tad bit less optimized, but it should be more randomized now.
		$query = "SELECT COUNT(*) FROM db_cache";
		$result = mysqli_query($mysqli,$query);
		$temp = mysqli_fetch_array($result);
		// initial index in OFFSET is 0, so we have to take 1 off
		$rand_max = $temp[0] - 1;

		$query = "SELECT * FROM db_cache LIMIT 1 OFFSET " . mt_rand(0,$rand_max);
		$result = mysqli_query($mysqli,$query);

		if(!isset($result)) {
			echo "NO SQL RESULT GIVEN";
			break;
		}
		
		$row = mysqli_fetch_array($result);

		// temporary fix to the repetition issue
		// this'll have to be reworked when we add in the play queue
		while($row['TRACKID'] == $previous_song) {
			// had to add this in place of $query so it stays random
			// we REALLY need to get the play queue going ffs
			$result = mysqli_query($mysqli,"SELECT * FROM db_cache LIMIT 1 OFFSET " . mt_rand(0,$rand_max));
			$row = mysqli_fetch_array($result);
		}
		*/

		// BECAUSE I CAN'T CONCENTRATE (delete this later)
		// get the row count in the main cache
		$query = "SELECT COUNT(*) FROM db_cache";
		$result_init1 = mysqli_query($mysqli,$query);
		$temp = mysqli_fetch_array($result_init1);
		$rand_max = $temp[0] - 1;

		// get a track
		$query = 'SELECT * FROM play_queue ORDER BY ISNULL(play_queue.ADDED_BY) LIMIT 1 OFFSET 0';
		$result = mysqli_query($mysqli,$query);
		// if one isn't obtained, assume the queue is empty
		if(!mysqli_num_rows($result)) {
			// initiate the play queue, basically
			// use this as a fallback in case of an emergency, /actually/ initiate this in the setup eventually

			// go ahead and add 15 tracks to it
			for ($i=0;$i<15;$i++) { 
				$rand = mt_rand(0,$rand_max);

				while ($rand == $previous_song) {
					$previous_song = mt_rand(0,$rand_max);
				}

				$query = "SELECT * FROM db_cache LIMIT 1 OFFSET " . $rand;
				$result_init2 = mysqli_query($mysqli,$query);

				// if something really is wrong, quit entirely
				if(!mysqli_num_rows($result_init2)) {
					echo "NO SQL RESULT GIVEN (QUEUE INIT FALLBACK)";
					die();
				}

				// get a random track
				$row = mysqli_fetch_array($result_init2);
				// add it
				$query = 'INSERT INTO play_queue ( TRACKID, SERVICE, ADDED_ON ) VALUES ( "' . $row['TRACKID'] . '", "' . $row['SERVICE'] . '", ' . time() . ')';
				$result = mysqli_query($mysqli,$query);
			}
			// restart the loop
			$time = 0;
			continue;
		} else {
			// if one is obtained, grab it
			$selection = mysqli_fetch_array($result);

			// find the track in the db
			$query = 'SELECT * FROM db_cache WHERE TRACKID="' . $selection['TRACKID'] . '"';
			$result = mysqli_query($mysqli,$query);
			// selected track's info, THIS IS USED IN THE REST OF THE SCRIPT
			$row = mysqli_fetch_array($result);

			// delete it from the queue
			$query = 'DELETE FROM play_queue ORDER BY ISNULL(play_queue.ADDED_BY) LIMIT 1';
			mysqli_query($mysqli,$query);

			// if it WAS NOT ADDED MANUALLY
			if(!isset($selection['ADDED_BY'])) {
				// select a new random track to add
				$query = "SELECT * FROM db_cache LIMIT 1 OFFSET " . mt_rand(0,$rand_max);
				$result = mysqli_query($mysqli,$query);
				$temp_row = mysqli_fetch_array($result);
				$query = 'INSERT INTO play_queue ( TRACKID, SERVICE, ADDED_ON ) VALUES ( "' . $temp_row['TRACKID'] . '", "' . $temp_row['SERVICE'] . '", ' . time() . ')';
				$result = mysqli_query($mysqli,$query);
			}
		}

		switch ($row['SERVICE']) {
			case 'SDCL':
				$stream_link = $row['RETURN_ARG5'] . "?client_id=" . $sc_api_key;
				break;

			case 'WYZL':
				$stream_link = $row['RETURN_ARG5'];
				break;

			case 'JMND':
				$stream_link = $row['RETURN_ARG5'];
				break;

			default:
				$stream_link = $row['RETURN_ARG5'];
				break;
		}

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $stream_link);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($curl, CURLOPT_HEADER, true);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0");
		$output = curl_exec($curl);

		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($httpCode == 404) {
			echo "RETURNED 404: " . $stream_link;
			// should we go ahead and remove the track from the DB, or mark it with a warning?
			$time = 0;
			curl_close($curl);
			continue;
		}

		curl_close($curl);

		$url_str = $row['RETURN_ARG3'] . " - " . $row['RETURN_ARG2'];
		echo $url_str;

		putenv("ICHOST=" . $icecast['host']);
		putenv("ICPORT=" . $icecast['port']);
		putenv("ICMOUNT=" . $icecast['mount']);
		putenv("ICMOUNT_LQ=" . $icecast['mountlq']);
		putenv("ICADMIN_USER=" . $icecast['admin_user']);
		putenv("ICADMIN_PASS=" . $icecast['admin_pass']);

		// anything i'm trying to do involving escaping flat out fails, so i caved and i'm doing this -.-
		$cmd_str = str_replace('\\', '\\\\', $url_str);
		$cmd_str = str_replace('$', '\$', $cmd_str);
		$cmd_str = str_replace('"', '\"', $cmd_str);
		exec('./metadata_upd "' . $cmd_str . '" > metadata_log 2>&1 &');

		$time = time();

		$query = 'SELECT TRACKID FROM play_history';
		$result = mysqli_query($mysqli,$query);
		$records = mysqli_num_rows($result);

		if($records > 100) {
			$query = "DELETE FROM play_history LIMIT 1";
			$result = mysqli_query($mysqli,$query);
		}

		// we'll add in ADDED_BY once we get the play queue going, for now everything there should be blank.
		if(isset($selection['ADDED_BY'])) {
			$query = 'INSERT INTO play_history ( TRACKID, SERVICE, PLAYED_ON, ADDED_BY ) VALUES ( "' . $row['TRACKID'] . '", "' . $row['SERVICE'] . '", ' . time() . ', "' . $selection['ADDED_BY'] . '")';
		} else {
			$query = 'INSERT INTO play_history ( TRACKID, SERVICE, PLAYED_ON ) VALUES ( "' . $row['TRACKID'] . '", "' . $row['SERVICE'] . '", ' . time() . ')';
		}
		$result = mysqli_query($mysqli,$query);

		$query = 'UPDATE db_cache SET PLAYING=0 WHERE PLAYING=1';
		$result = mysqli_query($mysqli,$query);

		$playcount = $row['PLAY_COUNT'] + 1;
		$query = 'UPDATE db_cache SET PLAYING=1,PLAY_COUNT=' . $playcount . ' WHERE TRACKID="' . $row['TRACKID'] . '"';
		$result = mysqli_query($mysqli,$query);

		exec($icecast['ffmpeg'] . ' -hide_banner -re -i \'' . $stream_link . '\' -acodec libmp3lame -q ' . $icecast['qual'] . ' -content_type "audio/mpeg3" "icecast://source:' . $icecast['pass'] . '@' . $icecast['host'] . ':' . $icecast['port'] . '/' . $icecast['mount'] . '" -acodec libmp3lame -q ' . $icecast['quallq'] . ' -content_type "audio/mpeg3" "icecast://source:' . $icecast['pass'] . '@' . $icecast['host'] . ':' . $icecast['port'] . '/' . $icecast['mountlq'] . '" 1> ../includes/ffmpeg_info.txt 2>&1');

	}
?>
