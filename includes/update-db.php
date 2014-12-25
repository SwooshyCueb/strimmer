<?php
// When do we run this? 

include dirname(__FILE__) . "/settings.php";

function install_1() {
	include dirname(__FILE__) . "/settings.php";
	$query = "CREATE TABLE strimmer_db (
		FIELD varchar(128) NOT NULL,
		VALUE varchar(2048),
		PRIMARY KEY (FIELD)
		);";
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	mysqli_free_result($result);
	$query = 'INSERT INTO strimmer_db ( FIELD,VALUE ) VALUES (
		"DB_VER",
		"1"
		);';
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	mysqli_free_result($result);
}

function update_1() {
	include dirname(__FILE__) . "/settings.php";
	$query = "DROP TABLE mpdi_db;";
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	mysqli_free_result($result);
}

function update_2() {
	include dirname(__FILE__) . "/settings.php";
	$query = "ALTER TABLE user_db ADD COLUMN SC_API_ID varchar(64);";
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	var_dump($result);
	mysqli_free_result($result);
	$query = "ALTER TABLE user_db ADD COLUMN SC_API_SECRET varchar(64);";
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	var_dump($result);
	mysqli_free_result($result);
	$query = "ALTER TABLE user_db ADD COLUMN PASS_VER int(8);";
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	var_dump($result);
	mysqli_free_result($result);
	$query = "UPDATE user_db SET PASS_VER=1;";
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	var_dump($result);
	mysqli_free_result($result);
	$query = "UPDATE strimmer_db SET DB_VER='2';";
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	var_dump($result);
	mysqli_free_result($result);
}

function update_3() {
	include dirname(__FILE__) . "/settings.php";
	$query = "ALTER TABLE user_db ADD COLUMN ADMIN int(4);";
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	var_dump($result);
	mysqli_free_result($result);
	$query = "UPDATE strimmer_db SET DB_VER='3';";
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	var_dump($result);
	mysqli_free_result($result);
}

function update_4() {
	include dirname(__FILE__) . "/settings.php";
	$query = "SELECT TRACKID,SERVICE_ARG2 FROM db WHERE SERVICE='SDCL';";
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	var_dump($result);
	while ($entry = mysqli_fetch_array($result)) {
		$query = "UPDATE db SET SERVICE_ARG1='" . $entry['SERVICE_ARG2'] . "', SERVICE_ARG2=NULL WHERE TRACKID='" . $entry['TRACKID'] . "';";
		$result2 = mysqli_query($mysqli,$query);
		var_dump($result2);
		mysqli_free_result($result2);
	}
	mysqli_free_result($result);
	$query = "UPDATE strimmer_db SET DB_VER='4';";
	$result = mysqli_query($mysqli,$query);
	// Error checking 'n' shit
	var_dump($result);
	mysqli_free_result($result);
}

$query = "SELECT * FROM mpdi_db;";
$dbver_res = mysqli_query($mysqli,$query);
if ($dbver_res !== FALSE) {
	// This probably means we don't have the table containing
	// database schema version information, so let's create it.
	update_1();
}
mysqli_free_result($dbver_res);

$query = "SELECT * FROM strimmer_db WHERE FIELD='DB_VER';";
$dbver_res = mysqli_query($mysqli,$query);
if ($dbver_res == FALSE) {
	// This probably means we don't have the table containing
	// database schema version information, so let's create it.
	install_1();
	mysqli_free_result($dbver_res);	
} else {
	$dbver_row = mysqli_fetch_array($dbver_res);
	switch ($dbver_row['VALUE']) {
		case '1':
			update_2();
		case '2':
			update_3();
		case '3':
			update_4();
		case '4':
			// We're good.
			break;
		default:
			// We're fucked.
			break;
	}
}
mysqli_free_result($dbver_res);

?>