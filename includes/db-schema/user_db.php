<?php

$db_schema['user_db']['ID']['Type'] = "int(32)";
$db_schema['user_db']['ID']['Null'] = "NO";
$db_schema['user_db']['ID']['Key'] = "PRI";
$db_schema['user_db']['ID']['Default'] = NULL;
$db_schema['user_db']['ID']['Extra'] = "auto_increment";

$db_schema['user_db']['USERNAME']['Type'] = "varchar(64)";
$db_schema['user_db']['USERNAME']['Null'] = "YES";
$db_schema['user_db']['USERNAME']['Key'] = "";
$db_schema['user_db']['USERNAME']['Default'] = "Anonymous User";
$db_schema['user_db']['USERNAME']['Extra'] = "";

$db_schema['user_db']['PASSWORD']['Type'] = "mediumtext";
$db_schema['user_db']['PASSWORD']['Null'] = "YES";
$db_schema['user_db']['PASSWORD']['Key'] = "";
$db_schema['user_db']['PASSWORD']['Default'] = NULL;
$db_schema['user_db']['PASSWORD']['Extra'] = "";

$db_schema['user_db']['TIMEZONE']['Type'] = "varchar(1000)";
$db_schema['user_db']['TIMEZONE']['Null'] = "YES";
$db_schema['user_db']['TIMEZONE']['Key'] = "";
$db_schema['user_db']['TIMEZONE']['Default'] = "America/Chicago";
$db_schema['user_db']['TIMEZONE']['Extra'] = "";

$db_schema['user_db']['LASTACTIVE']['Type'] = "int(255)";
$db_schema['user_db']['LASTACTIVE']['Null'] = "YES";
$db_schema['user_db']['LASTACTIVE']['Key'] = "";
$db_schema['user_db']['LASTACTIVE']['Default'] = "0";
$db_schema['user_db']['LASTACTIVE']['Extra'] = "";

?>