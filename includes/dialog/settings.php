<?php
	include_once dirname(dirname(__FILE__)) . "/settings.php";
	include_once dirname(dirname(__FILE__)) . "/session.php";

	if ($_SESSION['login'] == FALSE) {
		header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "login/");
		die();
	}

	$query = "SELECT USERNAME,TIMEZONE FROM user_db WHERE USERNAME=\"" . $_SESSION['username'] . "\"";
	$result = mysqli_query($mysqli,$query);
?>

<div class="dialog">
	<form name='mpd-set-form' id='mpd-set-form' action="includes/change_settings.php" method="post">
		<div style="width: 500px;">
			<span style="text-align: left;">Timezone</span>
			<div style="float: right;">
				<select name="timezone" style="width: 404px;" required>
					<?php
						foreach(timezone_identifiers_list() as $timezone) {
							if($timezone == $row['TIMEZONE'])
								echo '<option value="' . $timezone . '" selected>' . $timezone . '</option>';
							else
								echo '<option value="' . $timezone . '">' . $timezone . '</option>';
						}
					?>
				</select>
			</div>
			<br/>
			<br/>
			<span style="text-align: left;">Theme</span>
			<div style="float: right;">
				<select name="theme" style="width: 404px;" required>
					<?php
						$directory = array_diff(scandir(dirname(dirname(dirname(__FILE__))) . "/" . "css/themes"), array('..', '.'));
						foreach($directory as $file) {
							$file = str_replace(".php","",$file);
							if($file == $row['THEME'])
								echo '<option value="' . $file . '" selected>' . $file . '</option>';
							else
								echo '<option value="' . $file . '">' . $file . '</option>';
						}
					?>
				</select>
			</div>
		</div>
		<div class="dialog_buttons">
			<span class="button" onClick="document.forms['mpd-set-form'].submit();">Save</span>
			<span class="button" id="close_button_dg">Cancel</span>
		</div>
	</form>
	<form name="avatar-change-form" action="includes/change_avatar.php" method="POST" enctype="multipart/form-data" style="padding-top: 28px;">
		<span style="text-align: left;">Avatar/Icon</span>
		<div style="float: right;">
			<input type="file" name="userfile" required/>
			<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>"/>
			<span class="button" onClick="document.forms['avatar-change-form'].submit();">Upload</span>
		</div>
	</form>
</div>
<div class="dialog_bg"></div>