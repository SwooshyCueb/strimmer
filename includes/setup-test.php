<?php

if ((!empty($_POST)) && ($_POST['setup'] == 'test')) {
	$failed = FALSE;
	$dbfixable = FALSE;
	$printatend = NULL;
	switch ($_POST['submit']) {
		case 'Test the generated config':
		case 'Test again':
			include dirname(dirname(__FILE__)) . "/config-working.php";
			?>
			<!DOCTYPE html>
			<html>
			<head>
			<meta charset="UTF-8">
			<title>MPD Interface setup</title>
			</head>
			<body>
			<h1>MPD Interface setup</h1>
			<hr />
			<h2>MySQL Testing</h2>
			<table>
				<?php $mysqli = new mysqli($sql['host'], $sql['user'], $sql['pass'], NULL, $sql['port']); ?>
				<tr>
					<th>Connection to DB server</th> <?php
					$ret = mysqli_connect_error();
					if ($ret == NULL) {
						echo '<td>Success</td>';
					} else {
						echo '<td>Error: ' . $ret . '</td>';
						$failed = TRUE;
					}
					?>
				</tr>
				<tr>
					<th>DB existence check</th> <?php
					if ($failed) {
						echo '<td>N/A</td>';
					} else {
						if (mysqli_select_db($mysqli, $sql['db'])) {
							echo '<td>Success</td>';
						} else {
							echo '<td>Failure</td>';
							$failed = TRUE;
						}
					}
				echo '</tr>';
				
				include dirname(__FILE__) . "/db-schema.php";
				foreach(array_keys($db_schema) as $table) {
					echo '<tr>';
						echo '<th>DB table existence check: ' . $table . '</th>';
						if ($failed) {
							echo '<td>N/A</td>';
						} else {
							$query = "SHOW TABLES LIKE '" . $table . "';";
							$result = mysqli_query($mysqli,$query);
							if (($result !== FALSE) && ($result !== NULL)) {
								echo '<td>Success</td>';
								$missingtable[$table] = FALSE;
							} else {
								echo '<td>Failure</td>';
								$missingtable[$table] = TRUE;
								$dbfixable = TRUE;
							}
							mysqli_free_result($result);
						}
					echo '</tr>';
					echo '<tr>';
						echo '<th>DB table column check: ' . $table . '</th>';
						if ($failed || $missingtable[$table]) {
							echo '<td>N/A</td>';
						} else {
							$badtable = FALSE;
							$missingcolumns = NULL;
							$malformedcolumns = NULL;
							foreach(array_keys($db_schema[$table]) as $col){
								$query = "SHOW COLUMNS FROM " . $table . " WHERE Field='" . $col . "';";
								$result = mysqli_query($mysqli,$query);
								if (($result == FALSE) || ($result == NULL)) {
									if ($missingcolumns == NULL)
									{
										$missingcolumns = "Missing columns: ";
									} else {
										$missingcolumns .= ", ";
									}
									$badtable = TRUE;
									$missingcolumns .= $col;
								} else {
									$coldat = mysqli_fetch_array($result);
									foreach(array_keys($db_schema[$table][$col]) as $attrib) {
										$printatend .= $attrib . ' of ' . $col . ' from ' . $table . ' - ';
										$printatend .= 'Schema: "' . $db_schema[$table][$col][$attrib] . '" ';
										$printatend .= 'DB: "' . $coldat[$attrib] . '" ';
										if ($coldat[$attrib] !== $db_schema[$table][$col][$attrib]) {
											$printatend .= 'NO MATCH!';
											if($malformedcolumns !== NULL)
												$malformedcolumns .= ', ';
											$malformedcolumns .= $attrib . ' of ' . $col . ' should be ';
											$malformedcolumns .= $db_schema[$table][$col][$attrib];
											$malformedcolumns .= ' but is actually ' . $coldat[$attrib];
											$badtable = TRUE;
										} else {
											$printatend .= 'MATCH!';
										}
										$printatend .= '<br />';
									}
								}
								mysqli_free_result($result);
							}
							if (($missingcolumns !== NULL) && ($malformedcolumns !== NULL))
								$missingcolumns .= "; ";
							if ($badtable == FALSE) {
								echo '<td>Success</td>';
							} else {
								echo '<td>Error: ' . $missingcolumns . $malformedcolumns . '</td>';
							}
						}
					echo '</tr>';
				}
				?>
			</table>
			<h2>Misc.</h2>
			<table>
				<tr>
					<th>Transcoder existence check</th> <?php
					if (shell_exec("which " . $icecast['ffmpeg']) == NULL) {
						echo '<td>Failure</td>';
					} else {
						echo '<td>Success</td>';
					} ?>
				</tr>
				<tr>
					<th>Soundcloud API key validity check</th>
					<td>Not implemented.</td>
				</tr>
			</table>
			<hr />
			<form action=<?php echo '"'.$here.'"'; ?> method="post">
				<input type="hidden" name="setup" value="test">
				<input type="submit" name="submit" value="Test again">
				<input type="submit" name="submit" value="Create DB tables">
				<input type="submit" name="submit" value="Implement this config">
			</form>
			</body>
			</html> <?php
			break;
		case 'Skip testing and create DB tables':
		case 'Create DB tables':
			include dirname(__FILE__) . "/setup-db.php";
			break;
		case 'Skip testing and implement new config':
		case 'Implement this config':
			if (rename(dirname(dirname(__FILE__)) . "/config-working.php", dirname(dirname(__FILE__)) . "/config.php") == FALSE) {
				header('HTTP/1.0 500 Internal Server Error');
				echo "Could not move config-working.php to config.php";
				die();
			} else {
				header("Location: " . $here);
			}
			break;
		default:
			break;
	}

}
