<?php
	header("Content-type: text/css");

	include_once dirname(dirname(__FILE__)) . "/includes/settings.php";
	include_once dirname(dirname(__FILE__)) . "/includes/session.php";

	// including the default theme as well in case something isn't defined
	include "themes/default.php";

	if(!isset($_SESSION['theme'])) {
		$_SESSION['theme'] = "default";
	}

	if(is_file("themes/" . $_SESSION['theme'] . ".php")) {
		// this should overwrite the default variables
		include "themes/" . htmlspecialchars($_SESSION['theme']) . ".php";
	} else {
		include "themes/default.php";
	}

	if($theme_settings['use_borders']) {
		$height_scale = "109px";
	} else {
		$height_scale = "108px";
	}
?>

@import url(http://fonts.googleapis.com/css?family=Roboto:100,300,400,700);
@import url(http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300);

@media only screen and (max-device-width: 480px) {
	.col2, .col_wrapper, .wrapper, { max-width: 100vw; min-width: 0 !important; }
	.song_list { max-width: calc(100vw - 32px); min-width: 0 !important; table-layout: fixed; }
	.song_list .song_row td:nth-child(1), .song_list .h_row td:nth-child(1) { width: calc(100vw - 56px); }
	.song_list .song_row td:nth-child(2), .song_list .h_row td:nth-child(2) { display: none; }
	.song_list .song_row td:nth-child(3), .song_list .h_row td:nth-child(3) { display: none; }
	.song_list .song_row td:nth-child(4), .song_list .h_row td:nth-child(4) { width: 24px; }
	.col3 {
		border: 0 !important;
		width: 100vw !important;
	}
	.col3_bg { width: 100vw !important; }
	#col3_wrapper {
		width: calc(100vw - 32px) !important;
	}
	.selected_info_title {
		width: calc(100vw - 140px) !important;
	}
	.selected_info_artist {
		width: calc(100vw - 140px) !important;
	}
	.selected_info_various {
		font-size: 9pt !important;
	}
	.selected_info_various tr td:nth-child(1) { 
		width: 25% !important;
	}
	.selected_info_various tr td:nth-child(2) { 
		width: 75% !important;
	}
	.footer .time {
		left: 0 !important;
		bottom: 0 !important;
		line-height: 20px !important;
		height: 20px !important;
		width: 72px !important;
		color: #fff !important;
		z-index: 500;
		background-color: rgba(0,0,0,0.5) !important;
		text-align: center !important;
	}
	.footer .time_elapsed {
		font-size: 10pt !important;
	}
	.footer .time_duration {
		font-size: 8pt !important;
	}
	.np_title {
		position: fixed;
		left: 80px;
		bottom: 32px;
		font-weight: 400 !important;
		font-size: 14pt !important;
	}
	.np_artist {
		position: fixed;
		left: 80px;
		bottom: 14px;
		font-size: 8pt !important;
	}
	.stats_section_wrapper {
		width: 100% !important;
	}
}
@media only screen and (min-device-width: 481px) {
	.col2 .col_wrapper, .wrapper, .song_list { min-width: 1000px !important; }
	.stats_section_wrapper {
		width: calc(100% / 3);
		float: left;
	}
}

body {
	font-family: "Roboto", "Segoe UI", "Helvetica", sans-serif;
	font-weight: 400;
	font-size: 12pt;
	color: <?php echo $font_color['body']; ?>;
	overflow: hidden;
}

hr {
	border: 0;
	border-bottom: 1px solid <?php echo $border_color['hr']; ?>;
}

h1 {
	font-weight: 700;
	font-size: 14pt;
	line-height: 32px;
}
strong {
	font-weight: 700;
}
h2 {
	font-weight: 700;
	font-size: 12pt;
	line-height: 32px;
}

.header {
	background-color: <?php echo $bg_color['header']; ?>;
	color: <?php echo $font_color['header']; ?>;
	height: 36px;
	width: 100%;
	position: fixed;
	top: 0;
	left: 0;
	line-height: 36px;
	z-index: 200;
}
.header .title {
	font-size: 15pt;
	font-weight: 700;
	line-height: 36px;
}
.header .left {
	float: left;
	display: inline-block;
	padding-left: 8px;
}
.header .right {
	float: right;
	display: inline-block;
	padding-right: 8px;
}

.header a {
	color: <?php echo $font_color['header']; ?>;
	text-decoration: none;
}
.header a:hover {
	color: #9cf;
}

.wrapper {
	position: relative;
	top: 36px;
}
.wrapper .col1 {
	opacity: 0;
	width: 220px;
	left: -220px;
	position: fixed;
	top: 36px;
	<?php if($theme_options['use_borders']) { ?>
	border-right: 1px solid <?php echo $border_color['col1']; ?>;
	<?php } ?>
	height: calc(100vh - <?php echo $height_scale; ?>);
	box-shadow: 1px 0px 6px rgba(0,0,0,0.3);
	overflow-y: auto;
	overflow-x: hidden;
}
.wrapper .col2 {
	height: calc(100vh - <?php echo $height_scale; ?>);
	font-size: 10pt;
	position: absolute;
	right: 0;
	width: 100%;
	overflow-y: hidden;
}
.wrapper .col3 {
	width: 500px;
	max-width: 500px;
	position: fixed;
	top: 36px;
	right: -500px;
	<?php if($theme_options['use_borders']) { ?>
	border-left: 1px solid <?php echo $border_color['col1']; ?>;
	<?php } ?>
	height: calc(100vh - <?php echo $height_scale; ?>);
	box-shadow: -1px 0px 6px rgba(0,0,0,0.3);
	z-index: 51;
	background-color: #000;
}

.wrapper .col1 {
	line-height: 30px;
	background-color: <?php echo $bg_color['col1']; ?>;
	z-index: 52;
}
.wrapper .col3 {
	line-height: 30px;
}
.wrapper .col1 .col_wrapper, .wrapper .col3 .col_wrapper {
	padding-top: 8px;
}
.wrapper .col1 .col_wrapper hr, .wrapper .col3 .col_wrapper hr {
	width: 220px;
	margin-left: 0px;
	margin-right: 0px;
}
.wrapper .col1 .col_wrapper div, .wrapper .col3 .col_wrapper {
	text-decoration: none;
	color: <?php echo $font_color['col1']; ?>;;
	padding-left: 4px;
	width: 216px;
	transition: .05s;
}
.wrapper .col1 a, .wrapper .col2 a, .wrapper .col3 a {
	text-decoration: none;
}
.wrapper .col1 .col_wrapper div:hover {
	background-color: <?php echo $bg_color['col1_hover']; ?>;
	color: <?php echo $font_color['col1_hover']; ?>;
	transition: .05s;
}
.wrapper .col2 .col_wrapper {
	height: 100%;
	overflow-y: scroll;
}
.sel_text {
	margin-left: 8px;
}

.sel_color_sc {
	color: <?php echo $service_color['soundcloud']; ?> !important;
	transition: .05s;
}
.sel_color_sc:hover {
	background-color: <?php echo $service_color['soundcloud']; ?> !important;
	color: <?php echo $service_color['soundcloud_hover']; ?> !important;
}
.sel_color_we {
	color: <?php echo $service_color['weasyl']; ?> !important;
	transition: .05s;
}
.sel_color_we:hover {
	background-color: <?php echo $service_color['weasyl']; ?> !important;
	color: <?php echo $service_color['weasyl_hover']; ?> !important;
}
.sel_color_jm {
	color: <?php echo $service_color['jamendo']; ?> !important;
	transition: .05s;
}
.sel_color_jm:hover {
	background-color: <?php echo $service_color['jamendo']; ?> !important;
	color: <?php echo $service_color['jamendo_hover']; ?> !important;
}
.sel_color_dl {
	color: <?php echo $service_color['plain']; ?> !important;
	transition: .05s;
}
.sel_color_dl:hover {
	background-color: <?php echo $service_color['plain']; ?> !important;
	color: <?php echo $service_color['plain_hover']; ?> !important;
}
.sel_color_hypem {
	color: <?php echo $service_color['hypem']; ?> !important;
	transition: .05s;
}
.sel_color_hypem:hover {
	background-color: <?php echo $service_color['hypem']; ?> !important;
	color: <?php echo $service_color['hypem_hover']; ?> !important;
}
.sel_color_yt {
	color: <?php echo $service_color['yt']; ?> !important;
	transition: .05s;
}
.sel_color_yt:hover {
	background-color: <?php echo $service_color['yt']; ?> !important;
	color: <?php echo $service_color['yt_hover']; ?> !important;
}

.col1 .col_wrapper {
	padding: 0px;
	width: 0px;
	position: static;
}

.oi {
	padding-right: 4px;
}

.user {
	margin-top: 5px;
	border: 1px solid <?php echo $border_color['user']; ?>;
	height: 24px;
	padding-right: 8px;
	background-color: <?php echo $bg_color['user']; ?>;
	transition: .1s;
	color: <?php echo $font_color['user']; ?>;
}
.user:hover {
	border: 1px solid <?php echo $border_color['user_hover']; ?>;
	color: <?php echo $font_color['user_hover']; ?>;
	transition: .1s;
}
.username {
	line-height: 28px;
	vertical-align: top;
	font-size: 10pt;
}
.user img {
	height: 24px;
	width: 24px;
	padding-right: 8px;
}

.footer {
	<?php if($theme_options['use_borders']) { ?>
	border-top: 1px solid <?php echo $border_color['footer']; ?>;
	<?php } ?>
	background-color: <?php echo $bg_color['footer']; ?>;
	z-index: 100;
	position: fixed;
	bottom: 0;
	left: 0;
	width: 100%;
	height: 56px;
	padding: 8px;
	padding-left: 0;
	color: <?php echo $font_color['footer']; ?>;
	box-shadow: inset 0px 8px 8px rgba(0,0,0,0.15);
}
.footer a {
	color: <?php echo $font_color['link_footer']; ?>;
	text-decoration: none;
}
.footer a:hover {
	color: <?php echo $font_color['link_footer_hover']; ?>;
}

.list_info a {
	color: <?php echo $font_color['link_list']; ?>;
	text-decoration: none;
	font-family: "Roboto Condensed";
}
.list_info a:hover {
	color: <?php echo $font_color['link_list_hover']; ?>;
}

.np_art {
	height: 73px;
	width: 73px;
	padding-right: 8px;
	float: left;
	position: relative;
	top: -8px;
}
.np_title {
	font-size: 18pt;
	font-weight: 300;
	line-height: 24pt;
	white-space: nowrap;
	overflow: hidden;
}
.np_artist {
	font-size: 11pt !important;
	line-height: 18pt;
	font-family: "Roboto Condensed";
	font-weight: 300;
	white-space: nowrap;
	overflow: hidden;
}
.np_who {
	font-size: 8pt;
	line-height: 14pt;
}

.song_list {
	border-collapse: separate;
	width: 100%;
	z-index: -1;
	vertical-align: middle;
}
.h_row {
	color: <?php echo $font_color['list_header']; ?>;
	font-weight: 700;
	background-color: <?php echo $bg_color['list_header']; ?>;
	padding: 8px;
}
.h_row td {
	padding: 8px;
}
.song_row {
	height: 32px;
	line-height: 48px;
}
.song_row:hover {
	background-color: <?php echo $bg_color['list_hover']; ?> !important;
	color: #000;
}
.song_row td {
	vertical-align: middle;
}
.song_row td:nth-child(n+2) {
	padding-left: 8px;
}
.song_row:nth-child(odd) {
	color: <?php echo $font_color['list_oddrows']; ?>;
	background-color: <?php echo $bg_color['list_oddrows']; ?>;
}
.song_row:nth-child(even) {
	color: <?php echo $font_color['list_evenrows']; ?>;
	background-color: <?php echo $bg_color['list_evenrows']; ?>;
}
.song_row td:nth-child(1) {
	width: 55%;
}
.song_row td:nth-child(2) {
	width: 25%;
}
.song_row td:nth-child(3) {
	width: 15%;
}
.song_row td:nth-child(4) {
	width: 4%;
}
.song_row td:nth-child(4) img {
	width: 24px;
}
.list_info {
	margin-top: 8px;
	position: relative;
	left: 8px;
	line-height: 16px;
}
.list_art {
	height: 48px;
	width: 48px;
	float: left;
	position: relative;
	z-index: 0;
	display: inline-block;
}
.list_title {
	font-size: 12pt;
}
.list_title, .list_artist {
	white-space: nowrap;
	overflow: hidden;
	padding-bottom: 2px;
}
.overflow_grd {
	width: calc(100% - 48px);
	position: absolute;
	left: 48px;
	height: 100%;
	z-index: 49;
	pointer-events: none;
}
.song_row:nth-child(even) .overflow_grd {
	background: linear-gradient(to left, <?php echo $bg_color['list_evenrows']; ?>, transparent 10%);
}
.song_row:nth-child(odd) .overflow_grd {
	background: linear-gradient(to left, <?php echo $bg_color['list_oddrows']; ?>, transparent 10%);
}
.song_row:hover .overflow_grd {
	background: linear-gradient(to left, <?php echo $bg_color['list_hover']; ?>, transparent 10%);
}

.playing {
	color: <?php echo $font_color['col1_hover']; ?> !important;
	background-color: <?php echo $bg_color['col1_hover']; ?> !important;
}
.playing .overflow_grd {
	background: linear-gradient(to left, <?php echo $bg_color['col1_hover']; ?>, transparent 10%) !important;
}
.playing:hover {
	color: <?php echo $font_color['col1_hover']; ?> !important;
	background-color: <?php echo $bg_color['col1_hover']; ?> !important;
}
.playing:hover .overflow_grd {
	background: linear-gradient(to left, <?php echo $bg_color['col1_hover']; ?>, transparent 10%) !important;
}
.playing a {
	color: <?php echo $font_color['col1_hover']; ?> !important;
}

.dropdown {
	background-color: <?php echo $bg_color['user']; ?>;
	border: 1px solid <?php echo $border_color['user']; ?>;
	border-top: none;
	font-size: 10pt;
	position: fixed;
	right: 8px;
	top: 31px;
	z-index: 300;
	padding: 4px;
	display: none;
}
.dropdown_sel {
	padding: 4px;
	color: <?php echo $font_color['user']; ?>;
}
.dropdown_sel:hover {
	color: <?php echo $font_color['dropdown_hover']; ?>;
	background-color: <?php echo $bg_color['dropdown_hover']; ?>;
}

.dialog {
	box-shadow: 0px 12px 64px rgba(0,0,0,0.7);
	padding: 32px;
	max-width: 700px;
	z-index: 400;
	background-color: <?php echo $bg_color['dialog']; ?>;
	line-height: 16px;
	color: <?php echo $font_color['dialog']; ?>;
	position: fixed;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}
.dialog_bg {
	position: fixed;
	z-index: 399;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	background-color: rgba(0,0,0,0.7);
}
.dialog_buttons {
	width: 100%;
	text-align: center;
	padding-top: 32px;
}
.button {
	padding: 12px;
	margin: 6px;
	border-radius: 8px;
	background-color: <?php echo $bg_color['dialog_buttons']; ?>;
	color: <?php echo $font_color['dialog_buttons']; ?>;
}
.button:hover {
	background-color: <?php echo $bg_color['dialog_buttons_hover']; ?>;
	color: <?php echo $font_color['dialog_buttons_hover']; ?>;
}
.button:active {
	background-color: <?php echo $bg_color['dialog_buttons_clicked']; ?>;
	color: <?php echo $font_color['dialog_buttons_clicked']; ?>;
}

.dialog input, .dialog select {
	background-color: <?php echo $bg_color['dialog_input']; ?>;
	color: <?php echo $font_color['dialog_input']; ?>;
	padding: 4px;
	border: 1px solid <?php echo $border_color['dialog_input']; ?>;
	font-family: "Droid Sans", "Segoe UI", "Helvetica", sans-serif;
}
.dialog form {
	line-height: 24px;
}
#list_delete {
	float: right;
	font-size: 14pt;
	padding: 8px;
	padding-right: 8px;
	color: <?php echo $font_color['list_delete']; ?>;
	text-decoration: none;
}
#list_delete:hover {
	color: <?php echo $font_color['list_delete_hover']; ?>;
}
#list_queue {
	float: right;
	font-size: 14pt;
	padding: 8px;
	padding-right: 4px;
	color: <?php echo $font_color['list_delete']; ?>;
	text-decoration: none;
}
#list_queue:hover {
	color: <?php echo $font_color['list_delete_hover']; ?>;
}

.list_svc {
	width: 24px;
	height: auto;
}

.balloon {
	font-weight: 700;
	font-size: 7pt;
	padding-left: 4px;
	padding-right: 4px;
	border-radius: 3px;
	float: left;
	margin-right: 4px;
}

.col3_bg {
	position: fixed;
	z-index: 49;
	display: block;
	width: 500px;
	height: 100%;
	overflow: hidden;
}
.col3_bg img {
	width: auto;
	height: 110%;
	-webkit-filter: blur(25px);
	-moz-filter: blur(25px);
	-o-filter: blur(25px);
	-ms-filter: blur(25px);
	filter: blur(25px);
	margin-left: -50px;
	margin-top: -14px;
	opacity: 0.5;
}
#col3_wrapper {
	position: fixed;
	z-index: 51;
	width: 468px;
	padding: 16px;
	padding-bottom: 127px;
	top: 0px;
	bottom: 0;
	overflow-y: auto;
}

.selected_info_art {
	float: left;
	margin-right: 8px;
	box-shadow: 0px 4px 8px rgba(0,0,0,0.4);
}
.selected_info_title {
	color: #fff;
	font-weight: 700;
	font-size: 14pt;
	white-space: nowrap;
	overflow: hidden;
	width: 360px;
	text-overflow: ellipsis;
	text-shadow: 0px 1px 4px rgba(0,0,0,0.5);
}
.selected_info_artist {
	font-size: 14pt;
	white-space: nowrap;
	overflow: hidden;
	width: 360px;
	text-overflow: ellipsis;
	text-shadow: 0px 1px 2px rgba(0,0,0,0.5);
}
.selected_info_artist a {
	color: #8cf;
}

.selected_info_various {
	margin-top: 12px;
	margin-bottom: 12px;
	font-size: 10pt;
	float: left;
	width: 100%;
	table-layout: fixed;
}
.selected_info_various tr td:nth-child(1) { 
	width: 20%;
	font-weight: 700;
	text-shadow: 0px 1px 2px rgba(0,0,0,0.5);
	text-align: right;
	padding-right: 8px;
}
.selected_info_various tr td:nth-child(2) { 
	width: 80%;
	color: #fff;
	text-shadow: 0px 1px 2px rgba(0,0,0,0.3);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

#userlist_wrapper {
	padding: 16px;
	background-color: <?php echo $bg_color['list_oddrows']; ?>;
}
.userdiv {
	float: left;
	width: 102px;
	padding: 8px;
	margin: 8px;
	text-align: center;
	background-color: <?php echo $bg_color['list_evenrows']; ?>;
	box-shadow: 0px 2px 4px rgba(0,0,0,0.4);
}
.userdiv:hover {
	background-color: <?php echo $bg_color['list_hover']; ?>;
}
.userdiv_name {
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	color: <?php echo $font_color['list_evenrows']; ?>;
}
.userdiv_av {
	margin-bottom: 8px;
	border: 1px solid #000;
}

.col3_closer {
	font-size: 9pt;
	background-color: #000;
	color: #fff;
	transition: .25s;
	padding: 4px;
	position: fixed;
	top: 0;
	right: 0;
}
.col3_closer .oi {
	padding: 0;
}
.col3_closer:hover {
	color: #000;
	background-color: #fff;
	transition: .25s;
}
.col3_button_wrapper {
	float: left;
	width: 100%;
	text-align: center;
}
.col3_button {
	display: inline-block;
	width: 140px;
	font-size: 10pt;
	padding: 0px;
	margin: 6px;
	border-radius: 8px;
	text-align: center;
	box-shadow: 0px 2px 4px rgba(0,0,0,0.4);
	background-color: <?php echo $bg_color['dialog_buttons']; ?>;
	color: <?php echo $font_color['dialog_buttons']; ?>;
}
.col3_button:hover {
	background-color: <?php echo $bg_color['dialog_buttons_hover']; ?>;
	color: <?php echo $font_color['dialog_buttons_hover']; ?>;
}
.col3_button:active {
	background-color: <?php echo $bg_color['dialog_buttons_clicked']; ?>;
	color: <?php echo $font_color['dialog_buttons_clicked']; ?>;
}
#col3b_green { background-color: #394; }
#col3b_green:hover { background-color: #3b6; }
#col3b_green:active {
	background-color: #000;
	color: #fff;
}
#col3b_red { background-color: #c43; }
#col3b_red:hover { background-color: #f43; }
#col3b_red:active {
	background-color: #000;
	color: #fff;
}
#col3b_pink { background-color: #F06292; }
#col3b_pink:hover { background-color: #F48FB1; }
#col3b_pink:active {
	background-color: #000;
	color: #fff;
}
#col3b_disabled {
	background-color: #444;
	color: #777;
}

.footer .progress_bar {
	position: fixed;
	left: 72px;
	bottom: 0px;
	height: 72px;
	background-color: <?php echo $bg_color['col1_hover']; ?>;
	/*box-shadow: 0px 0px 8px <?php echo $bg_color['col1_hover']; ?>;*/
	opacity: 0.2;
	z-index: -1;
}
.footer .time {
	position: fixed;
	text-align: right;
	right: 16px;
	height: 72px;
	line-height: 72px;
	bottom: 0px;
	font-weight: 300;
	font-size: 24pt;
	color: <?php echo $font_color['footer']; ?>;
}
.footer .time .time_duration {
	font-size: 18pt;
}

.graph_bar {
	background-color: <?php echo $bg_color['col1_hover']; ?>;
	text-align: right;
	height: 24px;
}
.graph_bar_caption {
	color: <?php echo $font_color['col1_hover']; ?>;
	padding-right: 4px;
}

.stats_section {
	padding: 16px;
	margin: 16px;
	background-color: <?php echo $bg_color['list_hover']; ?>;
	color: <?php echo $font_color['list_hover']; ?>;
	box-shadow: 0px 2px 4px rgba(0,0,0,0.4);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.gen_stats {
	width: 100%;
	table-layout: fixed;
	line-height: 24px;
}
.gen_stats tr td:nth-child(1) { 
	width: 144px;
	font-weight: 700;
	text-align: right;
	padding-right: 8px;
}
.gen_stats tr td:nth-child(2) { 
	width: calc(100% - 144px);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.graph_bar_caption {
	white-space: nowrap;
	overflow: hidden;
	text-overflow: clip;
}

.dmca-table {
	width: 100%;
}
.dmca-table tr td {
	padding: 4px;
}