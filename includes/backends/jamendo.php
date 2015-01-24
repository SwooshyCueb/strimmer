<?php
include_once dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include_once dirname(dirname(__FILE__)) . "/functions.php";
include dirname(__FILE__) . "/jm-func.php";

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

if(isset($_POST['mode'])) {
	if(isset($_POST['jm_id'])) {
		switch ($_POST['mode']) {
			case 'add':
				$time = time();
				// do we check `is_numeric($_POST['jm_id'])`?
				$resolved_vars = json_decode(jamendo_resolveFromID($_POST['jm_id']),true);
				$result_vars = $resolved_vars['results'][0];
				$jm_trk_id = $result_vars['id'];
				$query = 'INSERT INTO db ( TRACKID,SERVICE,SERVICE_ARG1,ADDED_BY,ADDED_ON ) VALUES (
					"JMND' . $jm_trk_id . '",
					"JMND",
					"' . $jm_trk_id . '",
					"' . $_SESSION['username'] . '",
					' . $time . '
					)';
				$result = mysqli_query($mysqli,$query);
				if($result) {
					if(!isset($result_vars['audio'])) {
						$msg = "No stream URL could be obtained from " . $_POST['jm_id'] . ".";
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
					// I'm assuming jamendo provides some image regardless if there's one or not...
					/*
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
					*/

					$query = 'INSERT INTO db_cache ( TRACKID,SERVICE,RETURN_ARG1,RETURN_ARG2,RETURN_ARG3,RETURN_ARG4,RETURN_ARG5,RETURN_ARG6,RETURN_ARG7,ADDED_BY,ADDED_ON ) VALUES (
						"JMND' . $jm_trk_id . '",
						"JMND",
						' . $jm_trk_id . ',
						"' . $result_vars['name'] . '",
						"' . $result_vars['artist_name'] . '",
						"' . "https://jamendo.com/artist/" . $result_vars['artist_id'] . '",
						"' . $result_vars['audio'] . '",
						"' . $result_vars['shareurl'] . '",
						"' . $result_vars['album_image'] . '",
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
