<?php
include_once dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include_once dirname(dirname(__FILE__)) . "/functions.php";
include dirname(__FILE__) . "/hypem-func.php";

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
	if(isset($_POST['hypem_url'])) {
		switch ($_POST['mode']) {
			case 'add':
				$time = time();
				$hypem_track = hypem_getInfo($_POST['hypem_url']);
				// Validate here
				$query = 'INSERT INTO db ( TRACKID,SERVICE,SERVICE_ARG1,ADDED_BY,ADDED_ON ) VALUES (
					"HYPE' . $hypem_track["id"] . '",
					"HYPE",
					"' . $_POST['hypem_url'] . '",
					"' . $_SESSION['username'] . '",
					' . $time . '
					)';
				$result = mysqli_query($mysqli,$query);
				if($result) {
					// track id, title, owner account, stream url, permalink id
					/* 
						track id	RETURN_ARG1
						title		RETURN_ARG2
						artist		RETURN_ARG3
						owner link	RETURN_ARG4
						stream url	RETURN_ARG5
						permalink	RETURN_ARG6
					*/

					// dump album art

					$query = 'INSERT INTO db_cache ( TRACKID,SERVICE,RETURN_ARG1,RETURN_ARG2,RETURN_ARG3,RETURN_ARG4,RETURN_ARG5,RETURN_ARG6,ADDED_BY,ADDED_ON ) VALUES (
						"HYPE' . $hypem_track["id"] . '",
						"HYPE",
						"' . $hypem_track["id"] . '",
						"' . $hypem_track['title'] . '",
						"' . $hypem_track['artist'] . '",
						"#",
						"' . $hypem_track['url'] . '",
						"' . $_POST['hypem_url'] . '",
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
				} else {
					$query = 'SELECT TRACKID FROM db_cache WHERE TRACKID="HYPE' . $hypem_track["id"] . '" LIMIT 1';
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

?>