<?php
	include_once dirname(dirname(__FILE__)) . "/includes/settings.php";
	$time = 0;

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

		$url_str = $row['RETURN_ARG3'] . " - " . $row['RETURN_ARG2'];
		echo $url_str;
		/*
		$url = "http://" . $icecast['admin_user'] . ":" . $icecast['admin_pass'] . "@" . $icecast['host'] . ":" . $icecast['port'] . "/admin/metadata?mount=/ " . $icecast['mount'] . "&mode=updinfo&song=" . $url_str;
		// $icecast['mount']="silence.mp3" ????

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, $icecast['admin_user'] . ":" . $icecast['admin_pass']);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$output = curl_exec($curl);
		echo $url . "\r\n";
		echo $output . "\r\n";
		curl_close($curl);
		*/

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
