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
	<title><?php echo $prog_title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="css/main.php"/>
	<link rel="stylesheet" type="text/css" href="css/open-iconic.css"/>
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script>
	$(document).ready(function(){
		$('.col1').toggle()
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
		$("#add_we").on("click",function(){
			$(".dialog_load_spot").empty()
			$(".dialog_load_spot").load("includes/dialog/weasyl.html", function() {
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
		$("#col1_toggle").on("click",function(){
	        $('.col1').toggle("slide", "direction: left", 300);
		})
		$("#drop_logout").on("click",function(){
			window.location.href = "includes/logout.php";
		})
		$("#drop_login").on("click",function(){
			window.location.href = "login/";
		})
		$("#close_button_dg").on("click", function(){
			$(".dialog").fadeOut(200)
			$(".dialog_bg").fadeOut(200)
		})
		// we need to add $_GET['user'] back to this eventually
		$(".col2").load("includes/sections/browser.php");

		$("#library").on("click",function(){
			$(".col2").fadeOut(100,function(){
				$(".col2").empty();
				$(".col2").load("includes/sections/browser.php", function(){
					$(".col2").fadeIn(100);
				})
			})
		})
		$("#history").on("click",function(){
			$(".col2").fadeOut(100,function(){
				$(".col2").empty();
				$(".col2").load("includes/sections/history.php", function(){
					$(".col2").fadeIn(100);
				})
			})
		})

	});
	</script>
</head>

<body>
	<div class="dialog_load_spot"></div>
	<div class="header">
		<div class="left">
			<span class="title"><span class="oi" data-glyph="menu" id="col1_toggle" style="font-size: 12pt;"></span> <?php echo $prog_title; ?></span>
		</div>
		<div class="right">
			<div class="user">
				<img src="includes/avatars/<?php echo $_SESSION['username']; ?>.jpg"/><span class="username"><?php echo $_SESSION['username']; ?></span>
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
					<a href="?user=<?php echo $_SESSION['username']; ?>"><div class="panel_sel" id="myitems"><span class="sel_text"><span class="oi" data-glyph="person"></span> My Items</span></div></a>
					<hr/>
				<?php } ?>

				<a href="#"><div class="panel_sel" id="library"><span class="sel_text"><span class="oi" data-glyph="headphones"></span> Library</span></div></a>
				<a href="#"><div class="panel_sel" id="queue"><span class="sel_text"><span class="oi" data-glyph="list"></span> Play Queue</span></div></a>
				<a href="#"><div class="panel_sel" id="history"><span class="sel_text"><span class="oi" data-glyph="book"></span> Play History</span></div></a>

				<?php if ($_SESSION['login']) { ?>
					<hr/>
					<a href="#"><div class="panel_sel" id="userlist"><span class="sel_text"><span class="oi" data-glyph="people"></span> Userlist</span></div></a>
				<?php } ?>

				<hr/>
				<a href="<?php echo $icecast['url']; ?>"><div class="panel_sel"><span class="sel_text"><span class="oi" data-glyph="play-circle"></span> Listen</span></div></a>
				<a href="http://theblackparrot.us:8000/streamlq.mp3"><div class="panel_sel"><span class="sel_text"><span class="oi" data-glyph="play-circle"></span> Listen (LQ)</span></div></a>

				<?php if ($_SESSION['login']) { ?>
					<hr/>
					<a href="#"><div class="sel_color_sc" id="add_sc"><span class="sel_text"><span class="oi" data-glyph="plus"></span> SoundCloud Track</span></div></a>
					<a href="#"><div class="sel_color_we" id="add_we"><span class="sel_text"><span class="oi" data-glyph="plus"></span> Weasyl Submission</span></div></a>
				<?php } ?>
			</div>
		</div>
		<div class="col2">
		</div>
	</div>
</body>

</html>