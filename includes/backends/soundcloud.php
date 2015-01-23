<?php
include_once dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include_once dirname(dirname(__FILE__)) . "/functions.php";
include dirname(__FILE__) . "/sc-func.php";

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
					echo '<span class="button" id="retry_button_dg" onClick="button_retry();">Try again</span>';
					break;
				
				default:
					echo '<span class="button" id="close_button_dg">Cancel</span>';
					break;
			}
		}
	echo '</div>';
}

if(isset($_POST['mode'])) {
	if(isset($_POST['sc_url'])) {
		switch ($_POST['mode']) {
			case 'add':
				$time = time();
				$resolved_vars = json_decode(soundcloud_resolveFromURL($_POST['sc_url']),true);
				$stream_vars = json_decode(soundcloud_getStreamVars($resolved_vars['location']),true);
				$sc_trk_id = $stream_vars['id'];
				$query = 'INSERT INTO db ( TRACKID,SERVICE,SERVICE_ARG1,ADDED_BY,ADDED_ON ) VALUES (
					"SDCL' . $sc_trk_id . '",
					"SDCL",
					"' . $sc_trk_id . '",
					"' . $_SESSION['username'] . '",
					' . $time . '
					)';
				$result = mysqli_query($mysqli,$query);
				if($result) {
					
					$user_vars = $stream_vars['user'];

					if(!isset($stream_vars['stream_url'])) {
						$msg = "No stream URL could be obtained from " . $_POST['sc_url'] . ".";
						$buttons[1] = "ok";
						$buttons[2] = "add";
						echo(getDialog($msg,$buttons));
						exit;
					}
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

					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $artwork_url);
					curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
					curl_setopt($curl, CURLOPT_HEADER, true);  
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0");
					$output = curl_exec($curl);

					$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
					if($httpCode == 403) {
						$artwork_url = $user_vars['avatar_url'];
					}

					curl_close($curl);

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

					$msg = "Your track has been successfully added.";
					$buttons[1] = "ok";
					$buttons[2] = "add";
					echo(getDialog($msg,$buttons));
					//header("Location: " . $_SERVER['HTTP_REFERER']);
					exit;
				}

				break;
			
			default:
				break;
		}
	}
}
