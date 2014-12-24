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

$query = "SELECT * FROM mpdi_db;";
$dbver_res = mysqli_query($mysqli,$query);
var_dump($dbver_res);
if ($dbver_res !== FALSE) {
	// This probably means we don't have the table containing
	// database schema version information, so let's create it.
	update_1();
}
mysqli_free_result($dbver_res);

$query = "SELECT * FROM strimmer_db WHERE FIELD='DB_VER';";
$dbver_res = mysqli_query($mysqli,$query);
var_dump($dbver_res);
if ($dbver_res !== FALSE) {
	// This probably means we don't have the table containing
	// database schema version information, so let's create it.
	install_1();
	mysqli_free_result($dbver_res);	
} else {
	$dbver_row = mysqli_fetch_array($dbver_res);
	switch ($dbver_row['VALUE']) {
		case '1':
			// We're good.
			break;
		default:
			// We're fucked.
			break;
	}
}
mysqli_free_result($dbver_res);

?>