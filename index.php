<?php

if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include "includes/settings.php";
include_once "includes/session.php";
include "includes/functions.php";

if(isset($_GET['user'])) {
	$query = 'SELECT * FROM db_cache WHERE ADDED_BY="' . $_GET['user'] . '" ORDER BY ADDED_ON';
} else {
	$query = "SELECT * FROM db_cache ORDER BY ADDED_ON;";
}

$result = mysqli_query($mysqli,$query);

$user_nonexistant = 0;
if(!mysqli_num_rows($result)) {
	$user_nonexistant = 1;
}

?>

<html>

<head>
	<title><?php echo $prog_title; ?> - Library</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="css/main.php"/>
	<link rel="stylesheet" type="text/css" href="css/open-iconic.css"/>
	<style>
		.song_row_deleteanim {
			-webkit-animation: song_row_deleteanim 0.5s;
			animation: song_row_deleteanim 0.5s;
		}
		@-webkit-keyframes song_row_deleteanim {
			0% {
				opacity: 1;
			}
			100% {
				opacity: 0;
				-webkit-transform: translateX(-1000px);
			}
		}
		@keyframes song_row_deleteanim {
			0% {
				opacity: 1;
			}
			100% {
				opacity: 0;
				transform: translateX(-1000px);
			}
		}

		.col1_in {
			-webkit-animation: col1_in 0.3s;
			animation: col1_in 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		.col2-from_col1-in {
			-webkit-animation: col2-from_col1-in 0.3s;
			animation: col2-from_col1-in 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		.col1_out {
			-webkit-animation: col1_out 0.3s;
			animation: col1_out 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		.col2-from_col1-out {
			-webkit-animation: col2-from_col1-out 0.3s;
			animation: col2-from_col1-out 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		@-webkit-keyframes col1_in {
			0% {
				opacity: 0;
			}
			100% {
				opacity: 1;
				-webkit-transform: translateX(220px);
			}
		}
		@keyframes col1_in {
			0% {
				opacity: 0;
			}
			100% {
				opacity: 1;
				transform: translateX(220px);
			}
		}
		@-webkit-keyframes col2-from_col1-in {
			0% {
			}
			100% {
				-webkit-transform: translateX(220px);
			}
		}
		@keyframes col2-from_col1-in {
			0% {
			}
			100% {
				transform: translateX(220px);
			}
		}
		@-webkit-keyframes col1_out {
			0% {
				opacity: 1;
				-webkit-transform: translateX(220px);
			}
			100% {
				opacity: 0;
			}
		}
		@keyframes col1_out {
			0% {
				opacity: 1;
				transform: translateX(220px);
			}
			100% {
				opacity: 0;
			}
		}
		@-webkit-keyframes col2-from_col1-out {
			0% {
				-webkit-transform: translateX(220px);
			}
			100% {
			}
		}
		@keyframes col2-from_col1-out {
			0% {
				transform: translateX(220px);
			}
			100% {
			}
		}

		.col3_in {
			-webkit-animation: col3_in 0.3s;
			animation: col3_in 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		.col3_out {
			-webkit-animation: col3_out 0.3s;
			animation: col3_out 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		@-webkit-keyframes col3_in {
			0% {
				opacity: 0;
			}
			100% {
				opacity: 1;
				-webkit-transform: translateX(-500px);
			}
		}
		@keyframes col3_in {
			0% {
				opacity: 0;
			}
			100% {
				opacity: 1;
				transform: translateX(-500px);
			}
		}
		@-webkit-keyframes col3_out {
			0% {
				opacity: 1;
				-webkit-transform: translateX(-500px);
			}
			100% {
				opacity: 0;
			}
		}
		@keyframes col3_out {
			0% {
				opacity: 1;
				transform: translateX(-500px);
			}
			100% {
				opacity: 0;
			}
		}

		.dialog_in {
			-webkit-animation: dialog_in 0.3s;
			animation: dialog_in 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		.dialog_out {
			-webkit-animation: dialog_out 0.3s;
			animation: dialog_out 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		@-webkit-keyframes dialog_in {
			0% {
				opacity: 0;
				-webkit-transform: translate(-50%, -30%);
			}
			100% {
				opacity: 1;
				-webkit-transform: translate(-50%, -50%);
			}
		}
		@keyframes dialog_in {
			0% {
				opacity: 0;
				transform: translate(-50%, -30%);
			}
			100% {
				opacity: 1;
				transform: translate(-50%, -50%);
			}
		}
		@-webkit-keyframes dialog_out {
			0% {
				opacity: 1;
				-webkit-transform: translate(-50%, -50%);
			}
			100% {
				opacity: 0;
				-webkit-transform: translate(-50%, -30%);
			}
		}
		@keyframes dialog_out {
			0% {
				opacity: 1;
				transform: translate(-50%, -50%);
			}
			100% {
				opacity: 0;
				transform: translate(-50%, -30%);
			}
		}

		.fadein_full {
			-webkit-animation: fadein 0.3s;
			animation: fadein 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		.fadeout_full {
			-webkit-animation: fadeout 0.3s;
			animation: fadeout 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		.fadein_half {
			-webkit-animation: fadeinh 0.3s;
			animation: fadeinh 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		.fadeout_half {
			-webkit-animation: fadeouth 0.3s;
			animation: fadeouth 0.3s;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		@-webkit-keyframes fadein {
			0% { opacity: 0; }
			100% { opacity: 1; }
		}
		@keyframes fadein {
			0% { opacity: 0; }
			100% { opacity: 1; }
		}
		@-webkit-keyframes fadeout {
			0% { opacity: 1; }
			100% { opacity: 0; }
		}
		@keyframes fadeout {
			0% { opacity: 1; }
			100% { opacity: 0; }
		}
		@-webkit-keyframes fadeinh {
			0% { opacity: 0; }
			100% { opacity: 0.5; }
		}
		@keyframes fadeinh {
			0% { opacity: 0; }
			100% { opacity: 0.5; }
		}
		@-webkit-keyframes fadeouth {
			0% { opacity: 0.5; }
			100% { opacity: 0; }
		}
		@keyframes fadeouth {
			0% { opacity: 0.5; }
			100% { opacity: 0; }
		}
	</style>
	<script src="js/jquery.js"></script>
	<script src="js/jquery.mobile-1.4.5.js"></script>
	<script src="js/indexstuff.js.php"></script>
</head>

<body>
	<div class="dialog_load_spot"></div>
	<div class="header">
		<div class="left">
			<span class="title"><span class="oi" data-glyph="menu" id="col1_toggle" style="font-size: 12pt;"></span> <?php echo $prog_title; ?></span>
		</div>
		<div class="right">
			<div class="user">
				<img src="locdata/images/avatars/<?php echo $_SESSION['username']; ?>.jpg"/><span class="username"><?php echo $_SESSION['username']; ?></span>
			</div>
		</div>
	</div>


	<div class="footer">
		<div class="pb_load">
		</div>
		<div class="footer_load">
		</div>
	</div>

	<div class="dropdown">
		<?php if ($_SESSION['login']) { ?>
		<div class="dropdown_sel" id="drop_set">
			<span class="oi" data-glyph="wrench"></span> Settings
		</div>
		<div class="dropdown_sel" id="drop_logout">
			<span class="oi" data-glyph="account-logout"></span> Logout
		</div>
		<?php } else { ?>
		<div class="dropdown_sel" id="drop_login">
			<span class="oi" data-glyph="account-login"></span> Login
		</div>
		<?php } ?>
	</div>

	<div class="wrapper">
		<!-- column 1: panel selections -->
		<!-- column 2: selected panel -->
		<div class="col1">
			<div class="col_wrapper">
				<?php if ($_SESSION['login']) { ?>
					<a href="#"><div class="panel_sel" id="myitems"><span class="sel_text"><span class="oi" data-glyph="person"></span> My Items</span></div></a>
					<a href="#"><div class="panel_sel" id="favorites"><span class="sel_text"><span class="oi" data-glyph="thumb-up"></span> Favorites</span></div></a>
					<hr/>
				<?php } ?>

				<a href="#"><div class="panel_sel" id="library"><span class="sel_text"><span class="oi" data-glyph="headphones"></span> Library</span></div></a>
				<a href="#"><div class="panel_sel" id="queue"><span class="sel_text"><span class="oi" data-glyph="list"></span> Play Queue</span></div></a>
				<a href="#"><div class="panel_sel" id="history"><span class="sel_text"><span class="oi" data-glyph="book"></span> Play History</span></div></a>
				<hr/>
				<a href="#"><div class="panel_sel" id="statistics"><span class="sel_text"><span class="oi" data-glyph="graph"></span> Statistics</span></div></a>
				<?php if ($_SESSION['login']) { ?>
					<a href="#"><div class="panel_sel" id="userlist"><span class="sel_text"><span class="oi" data-glyph="people"></span> Userlist</span></div></a>
				<?php } ?>

				<hr/>
				<a href="<?php echo $icecast['url']; ?>"><div class="panel_sel"><span class="sel_text"><span class="oi" data-glyph="play-circle"></span> Listen (MP3)</span></div></a>
				<a href="<?php echo $icecast['url_lq']; ?>"><div class="panel_sel"><span class="sel_text"><span class="oi" data-glyph="play-circle"></span> Listen (LQ MP3)</span></div></a>
				<a href="<?php echo $icecast['url_opus']; ?>"><div class="panel_sel"><span class="sel_text"><span class="oi" data-glyph="play-circle"></span> Listen (Opus)</span></div></a>
				<a href="<?php echo $icecast['url_opuslq']; ?>"><div class="panel_sel"><span class="sel_text"><span class="oi" data-glyph="play-circle"></span> Listen (LQ Opus)</span></div></a>

				<?php if ($_SESSION['login']) { ?>
					<hr/>
					<a href="#"><div class="sel_color_dl" id="add_dl"><span class="sel_text"><span class="oi" data-glyph="plus"></span> Manual Submission</span></div></a>
					<a href="#"><div class="sel_color_jm" id="add_jm"><span class="sel_text"><span class="oi" data-glyph="plus"></span> Jamendo Track</span></div></a>
					<a href="#"><div class="sel_color_sc" id="add_sc"><span class="sel_text"><span class="oi" data-glyph="plus"></span> SoundCloud Track</span></div></a>
					<a href="#"><div class="sel_color_we" id="add_we"><span class="sel_text"><span class="oi" data-glyph="plus"></span> Weasyl Submission</span></div></a>
					<a href="#"><div class="sel_color_hypem" id="add_hypem"><span class="sel_text"><span class="oi" data-glyph="plus"></span> Hypem Track</span></div></a>
					<a href="#"><div class="sel_color_yt" id="add_yt"><span class="sel_text"><span class="oi" data-glyph="plus"></span> YouTube Video</span></div></a>
				<?php } ?>
				<hr/>
				<a href="#"><div class="panel_sel" id="about"><span class="sel_text"><span class="oi" data-glyph="star"></span> About</span></div></a>
				<?php if ($_SESSION['login']) { ?>
					<a href="#"><div class="panel_sel" id="export_db"><span class="sel_text"><span class="oi" data-glyph="data-transfer-download"></span> Export Library</span></div></a>
				<?php } ?>
			</div>
		</div>
		<div class="col2">
		</div>
		<div class="col3">
		</div>
	</div>
</body>

</html>