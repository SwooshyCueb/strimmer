<?php
include_once dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include_once dirname(dirname(__FILE__)) . "/functions.php";
include_once dirname(__FILE__) . "/wzl-func.php";

if($_SESSION['login'] == FALSE) {
	header('HTTP/1.0 401 Unauthorized');
	die();
}

if(isset($_POST['mode'])) {
	if(isset($_POST['wzl_sub_str'])) {
		switch ($_POST['mode']) {
			case 'add':
				$time = time();
				if (ctype_digit($_POST['wzl_sub_str'])) {
					$wzl_sub_id = $_POST['wzl_sub_str'];
				} else {
					$wzl_sub_id = wzl_subIDFromURL($_POST['wzl_sub_str']);
				}
				$sub_data = json_decode(wzl_resolveFromID($wzl_sub_id),true);
				if (!wzl_isUseableSubmission($sub_data)) {
					?>
					<strong>Error</strong>: Unuseable submission.
					<?php
					exit;
				}
				$query = 'INSERT INTO db ( TRACKID,SERVICE,SERVICE_ARG1,ADDED_BY,ADDED_ON ) VALUES (
					"WYZL' . $wzl_sub_id . '",
					"WYZL",
					"' . $wzl_sub_id . '",
					"' . $_SESSION['username'] . '",
					' . $time . '
					)';
				$result = mysqli_query($mysqli,$query);

				if($result) {
					$query = 'INSERT INTO db_cache ( TRACKID,SERVICE,RETURN_ARG1,RETURN_ARG2,RETURN_ARG3,RETURN_ARG4,RETURN_ARG5,RETURN_ARG6,RETURN_ARG7,ADDED_BY,ADDED_ON ) VALUES (
						"WYZL",
						"WYZL' . $wzl_sub_id . '",
						'  . $wzl_sub_id . ',
						"' . $sub_data['title'] . '",
						"' . $sub_data['owner'] . '",
						"https://www.weasyl.com/~' . $sub_data['owner_login'] . '",
						"' . $sub_data['media']['submission'][0]['url'] . '",
						"' . $sub_data['link'] . '",
						"' . wzl_getAlbumArt($sub_data) . '",
						"' . $_SESSION['username'] . '",
						' . $time . '
						)';
					$result = mysqli_query($mysqli,$query);
					exec("mpc -h " . $mpd['password'] . "@" . $mpd['host'] . " -p " . $mpd['port'] . " add " . $sub_data['media']['submission'][0]['url']);
				}
				header("Location: " . $_SERVER['HTTP_REFERER']);
				exit;
			
			default:
				break;
		}
	}
}
