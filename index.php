<?php

include "includes/settings.php";
include "includes/functions.php";
include_once "includes/session.php";

$query = "SELECT * FROM db_cache";
$result = mysqli_query($mysqli,$query);

?>

<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="css/main.css"/>
	<link rel="stylesheet" type="text/css" href="css/open-iconic.css"/>
	<script src="js/jquery.js"></script>
	<script>
	$(document).ready(function(){
		$(".dropdown").css("width",$(".user").css("width"))
		$(".user").on("click",function(){
			$(".dropdown").fadeIn(100)
			$(this).css("background-color","#fff")
			$(this).css("color","#000")
			$(this).css("border","1px solid #fff")
		})
		$(".user").on("mouseenter",function() {
			$(this).css("background-color","#0af")
			$(this).css("color","#fff")
			$(this).css("border","1px solid #fff")
		})
		$(".user").on("mouseleave",function() {
			$(this).css("background-color","#111")
			$(this).css("color","#fff")
			$(this).css("border","1px solid #000")
		})
		$(".dropdown").on("mouseenter",function(){
			$(".user").css("background-color","#fff")
			$(".user").css("color","#000")
			$(".user").css("border","1px solid #fff")
		})
		$(".dropdown").on("mouseleave",function(){
			$(this).fadeOut(100)
			$(".user").css("background-color","#111")
			$(".user").css("color","#fff")
			$(".user").css("border","1px solid #000")
		})
		$("#add_sc").on("click",function(){
			$(".dialog_load_spot").empty()
			$(".dialog_load_spot").load("includes/dialog/soundcloud.html", function() {
				$(".dialog").fadeOut(0)
				$(".dialog_bg").fadeOut(0)
				$(".dialog").fadeIn(200)
				$(".dialog_bg").fadeIn(200)
			})
		})
		$("#drop_set").on("click",function(){
			$(".dialog_load_spot").empty()
			$(".dialog_load_spot").load("includes/dialog/settings.php", function() {
				$(".dialog").fadeOut(0)
				$(".dialog_bg").fadeOut(0)
				$(".dialog").fadeIn(200)
				$(".dialog_bg").fadeIn(200)
			})
		})
		$("#drop_logout").on("click",function(){
			window.location.href = "includes/logout.php";
		})
		$("#close_button_dg").on("click", function(){
			$(".dialog").fadeOut(200)
			$(".dialog_bg").fadeOut(200)
		})
	});
	</script>
</head>

<body>
	<div class="dialog_load_spot"></div>
	<div class="header">
		<div class="left">
			<span class="title">MPD Interface</span>
		</div>
		<div class="right">
			<div class="user">
				<img src="includes/avatars/<?php echo $_SESSION['username']; ?>.jpg"/><span class="username"><?php echo $_SESSION['username']; ?></span>
			</div>
		</div>
	</div>

	<div class="wrapper">
		<!-- column 1: panel selections -->
		<!-- column 2: selected panel -->
		<div class="col1">
			<div class="col_wrapper">
				<a href="#"><div class="panel_sel"><span class="sel_text"><span class="oi" data-glyph="person"></span> My Items</span></div></a>
				<hr/>
				<a href="#"><div class="panel_sel"><span class="sel_text"><span class="oi" data-glyph="headphones"></span> Library</span></div></a>
				<a href="#"><div class="panel_sel"><span class="sel_text"><span class="oi" data-glyph="list"></span> Play Queue</span></div></a>
				<hr/>
				<a href="#"><div class="panel_sel"><span class="sel_text"><span class="oi" data-glyph="people"></span> Userlist</span></div></a>
				<hr/>
				<a href="#"><div class="sel_color_sc" id="add_sc"><span class="sel_text"><span class="oi" data-glyph="plus"></span> SoundCloud Track</span></div></a>
			</div>
		</div>
		<div class="col2">
			<div class="col_wrapper">
				<table class="song_list">
					<tr class="h_row">
						<td>Song</td>
						<td>Added by</td>
						<td>Added on</td>
					</tr>
					<?php
						while($row = mysqli_fetch_array($result)) {
							getListRow_Service($row);
						}
					?>
			</div>
		</div>
	</div>

	<div class="footer">
		<img src="images/icon.png" class="np_art"/>
		<span class="np_title">Title</span><br/>
		<span class="np_artist"><a href="#">[SC Account]</a></span><br/>
		<span class="np_who">Added by <a href="#">[username]</a></span>
	</div>

	<div class="dropdown">
		<div class="dropdown_sel" id="drop_set">
			<span class="oi" data-glyph="wrench"></span> Settings
		</div>
		<div class="dropdown_sel" id="drop_logout">
			<span class="oi" data-glyph="account-logout"></span> Logout
		</div>
	</div>
</body>

</html>