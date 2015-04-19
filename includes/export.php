<?php
	include_once dirname(__FILE__) . "/settings.php";
	include_once dirname(__FILE__) . "/session.php";

	if($_SESSION['login'] == FALSE) {
		header('HTTP/1.0 401 Unauthorized');
		die();
	}

	$date = date("MdY-His");

	$query = "SELECT * FROM db_cache ORDER BY ADDED_ON";
	$result = mysqli_query($mysqli,$query);

	$type = htmlspecialchars($_GET['type']);
	switch($type) {
		case 'json':
			header("Content-type: application/json");
			header("Cache-Control: no-store, no-cache");
			header('Content-Disposition: attachment; filename="strimmer_library-' . $date . '.json"');
			while($row = mysqli_fetch_array($result)) {
				$db[] = $row;
			}
			if(isset($_GET['pretty'])) {
				echo json_encode($db,JSON_PRETTY_PRINT);
			} else {
				echo json_encode($db);
			}
			break;

		case 'csv':
			header("Content-type: text/csv");
			header("Cache-Control: no-store, no-cache");
			header('Content-Disposition: attachment; filename="strimmer_library-' . $date . '.csv"');
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
				echo implode("\t",$row) . "\r\n";
			}
			break;
		
		default:
			header("Content-type: text/plain");
			header("Cache-Control: no-store, no-cache");
			header('Content-Disposition: attachment; filename="strimmer_library-' . $date . '.txt"');
			while($row = mysqli_fetch_array($result)) {
				$db[] = $row;
			}
			var_export($db);
			break;
	}

//MYSQLI_ASSOC