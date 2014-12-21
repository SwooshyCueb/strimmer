<?php
include_once dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include_once dirname(dirname(__FILE__)) . "/functions.php";
include dirname(__FILE__) . "/sc-func.php";

if($_SESSION['login'] == FALSE) {
	header('HTTP/1.0 401 Unauthorized');
	die();
}

if(isset($_POST['mode'])) {
	if(isset($_POST['mpdi_url'])) {
		switch ($_POST['mode']) {
			case 'add':
				$time = time();
				$resolved_vars = json_decode(soundcloud_resolveFromURL($_POST['mpdi_url']),true);
				$stream_vars = json_decode(soundcloud_getStreamVars($resolved_vars['location']),true);
				$sc_trk_id = $stream_vars['id'];
				$query = 'INSERT INTO db ( TRACKID,SERVICE,SERVICE_ARG1,SERVICE_ARG2,ADDED_BY,ADDED_ON ) VALUES (
					"SDCL' . $sc_trk_id . '",
					"SDCL",
					"' . $_POST['mpdi_url'] . '",
					"' . $sc_trk_id . '",
					"' . $_SESSION['username'] . '",
					' . $time . '
					)';
				echo $query;
				$result = mysqli_query($mysqli,$query);
				if($result) {
					
					$user_vars = $stream_vars['user'];

					if(isset($stream_vars['stream_url'])) {
						// track id, title, owner account, stream url, permalink id
						/* 
							track id	RETURN_ARG1
							title		RETURN_ARG2
							owner acc.	RETURN_ARG3
							owner link	RETURN_ARG4
							stream url	RETURN_ARG5
							permalink	RETURN_ARG6
							art link	RETURN_ARG7
						*/
						if(!isset($stream_vars['artwork_url'])) {
							$artwork_url = $user_vars['avatar_url'];
						} else {
							$artwork_url = $stream_vars['artwork_url'];
						}
						$query = 'INSERT INTO db_cache ( TRACKID,SERVICE,RETURN_ARG1,RETURN_ARG2,RETURN_ARG3,RETURN_ARG4,RETURN_ARG5,RETURN_ARG6,RETURN_ARG7,ADDED_BY,ADDED_ON ) VALUES (
							"SDCL' . $sc_trk_id . '",
							"SDCL",
							' . $sc_trk_id . ',
							"' . $stream_vars['title'] . '",
							"' . $user_vars['username'] . '",
							"' . $user_vars['permalink_url'] . '",
							"' . $stream_vars['stream_url'] . '",
							"' . $stream_vars['permalink_url'] . '",
							"' . $artwork_url . '",
							"' . $_SESSION['username'] . '",
							' . $time . '
							)';
						$result = mysqli_query($mysqli,$query);
						exec("mpc -h " . $mpd['password'] . "@" . $mpd['host'] . " -p " . $mpd['port'] . " add " . $stream_vars['stream_url'] . "?client_id=" . $sc_api_key);
					}
					header("Location: " . $_SERVER['HTTP_REFERER']);
					exit;
				}

				break;
			
			default:
				break;
		}
	}
}
