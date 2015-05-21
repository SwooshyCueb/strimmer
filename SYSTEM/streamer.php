<?php
	include_once dirname(dirname(__FILE__)) . "/includes/settings.php";
	include_once dirname(dirname(__FILE__)) . "/includes/backends/yt-func.php";
	include_once dirname(dirname(__FILE__)) . "/config.php";

	$time = 0;
	$previous_song = "";
	$good_track_found = 0;
	$goodCodes = array(302,200,201,203);

	while(true) {
		if(time() - $time <= 10) {
			sleep(15);
		}

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

				// make sure the track hasn't been detected as faulty before queueing it
				while(!$good_track_found) {
					if(isset($temp_row['ERRORCODE'])) {
						if(!in_array($temp_row['ERRORCODE'],$goodCodes)) {
							$query = "SELECT * FROM db_cache LIMIT 1 OFFSET " . mt_rand(0,$rand_max);
							$result = mysqli_query($mysqli,$query);
							$temp_row = mysqli_fetch_array($result);
						} else {
							$good_track_found = 1;
						}
					} else {
						$good_track_found = 1;
					}
				}
				$good_track_found = 0;

				$query = 'INSERT INTO play_queue ( TRACKID, SERVICE, ADDED_ON ) VALUES ( "' . $temp_row['TRACKID'] . '", "' . $temp_row['SERVICE'] . '", ' . time() . ')';
				$result = mysqli_query($mysqli,$query);
			}
		}

		switch ($row['SERVICE']) {
			case 'SDCL':
				$stream_link = $row['RETURN_ARG5'] . "?client_id=" . $sc_api_key;
				break;

			case 'WYZL':
			case 'JMND':
			case 'UNDF':
			case 'HYPE':
				$stream_link = $row['RETURN_ARG5'];
				break;

			case 'YTUB':
				$stream_link = getYouTubeData($row['RETURN_ARG5'],"StreamLink");
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
		
		$query = 'UPDATE db_cache SET ERRORCODE=' . $httpCode . ' WHERE TRACKID="' . $row['TRACKID'] . '"';
		mysqli_query($mysqli,$query);

		$goodCodes = array(302,200,201,203);
		if(!in_array($httpCode,$goodCodes)) {
			if($email['alerts_enabled']) {
				$subject = '[Strimmer] Attempted to play a faulty track (' . $row['TRACKID'] . ')';

				$message =  "This is an automated message from Strimmer. If you do not wish to see these messages, please disable them in your configuration file.\r\n";
				$message .= "\r\n";
				$message .= "The following track, " . $row['RETURN_ARG2'] . " by " . $row['RETURN_ARG3'] . " on " . $row['SERVICE'] . " [" . $row['TRACKID'] . "] has returned an error code of " . $httpCode . ".\r\n";
				$message .= "The track was skipped and has been tagged with the error code. It will no longer be played by Strimmer until the issue is resolved.";

				$headers   = array();
				$headers[] = "MIME-Version: 1.0";
				$headers[] = "Content-type: text/plain; charset=iso-8859-1";
				$headers[] = "From: {$email['from']}";
				$headers[] = "Reply-To: {$email['to']}";
				$headers[] = "Subject: {$subject}";
				$headers[] = "X-Mailer: PHP/".phpversion();

				mail($email['to'], $subject, $message, implode("\r\n", $headers));
			}

			echo "RETURNED $httpCode: " . $stream_link;
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
		putenv("ICMOUNT_OPUS=" . $icecast['mount_opus']);
		putenv("ICMOUNT_LQ_OPUS=" . $icecast['mountlq_opus']);
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

		exec($icecast['ffmpeg'] . ' -hide_banner -re -i \'' . $stream_link . '\' -codec:a libmp3lame -codec:v none -strict -2 -q ' . $icecast['qual'] . ' -content_type "audio/mpeg3" "icecast://source:' . $icecast['pass'] . '@' . $icecast['host'] . ':' . $icecast['port'] . '/' . $icecast['mount'] . '" -codec:a libmp3lame -codec:v none -strict -2 -q ' . $icecast['quallq'] . ' -content_type "audio/mpeg3" "icecast://source:' . $icecast['pass'] . '@' . $icecast['host'] . ':' . $icecast['port'] . '/' . $icecast['mountlq'] . '" -codec:a libopus -codec:v none -strict -2 -vbr on -compression_level 0 -frame_duration 40 -packet_loss 5 -b:a ' . $icecast['qual_opus'] . ' -content_type "audio/ogg" "icecast://source:' . $icecast['pass'] . '@' . $icecast['host'] . ':' . $icecast['port'] . '/' . $icecast['mount_opus'] . '" -codec:a libopus -codec:v none -strict -2 -vbr on -compression_level 0 -frame_duration 40 -packet_loss 5 -b:a ' . $icecast['quallq_opus'] . ' -content_type "audio/ogg" "icecast://source:' . $icecast['pass'] . '@' . $icecast['host'] . ':' . $icecast['port'] . '/' . $icecast['mountlq_opus'] . '" 1> ../includes/ffmpeg_info.txt 2>&1');
		// needed to start logging commands as of the YouTube update
		//file_put_contents(dirname(__FILE__) . "/ffmpeg_log.txt",$icecast['ffmpeg'] . ' -hide_banner -re -i \'' . $stream_link . '\' -codec:a libmp3lame -codec:v none -strict -2 -q ' . $icecast['qual'] . ' -content_type "audio/mpeg3" "icecast://source:' . $icecast['pass'] . '@' . $icecast['host'] . ':' . $icecast['port'] . '/' . $icecast['mount'] . '" -codec:a libmp3lame -codec:v none -strict -2 -q ' . $icecast['quallq'] . ' -content_type "audio/mpeg3" "icecast://source:' . $icecast['pass'] . '@' . $icecast['host'] . ':' . $icecast['port'] . '/' . $icecast['mountlq'] . '" 1> ../includes/ffmpeg_info.txt 2>&1');

	}
?>
