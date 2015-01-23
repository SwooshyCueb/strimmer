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
		.col1_out {
			-webkit-animation: col1_out 0.3s;
			animation: col1_out 0.3s;
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
	</style>
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script>
	var oldTrackID;
	<?php
		if(isset($_SESSION['username'])) {
			echo 'var usern = "' . $_SESSION['username'] . '";';
		}
	?>

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
				$(".dialog").hide()
				$(".dialog_bg").hide()
				$(".dialog").toggle("drop", {direction: "down"}, 300)
				$(".dialog_bg").fadeIn(200)
			})
		})
		$("#add_we").on("click",function(){
			$(".dialog_load_spot").empty()
			$(".dialog_load_spot").load("includes/dialog/weasyl.html", function() {
				$(".dialog").hide()
				$(".dialog_bg").hide()
				$(".dialog").toggle("drop", {direction: "down"}, 300)
				$(".dialog_bg").fadeIn(200)
			})
		})
		$("#drop_set").on("click",function(){
			$(".dialog_load_spot").empty()
			$(".dialog_load_spot").load("includes/dialog/settings.php", function() {
				$(".dialog").hide()
				$(".dialog_bg").hide()
				$(".dialog").toggle("drop", {direction: "down"}, 300);
				$(".dialog_bg").fadeIn(200)
			})
		})
		$("#col1_toggle").on("click",function(){
			//$('.col1').toggle("drop", {direction: "left"}, 300);
			if($('.col1').hasClass("col1_in")) {
				$('.col1').removeClass("col1_in")
				$('.col1').addClass("col1_out")
			} else {
				$('.col1').removeClass("col1_out")
				$('.col1').addClass("col1_in")
			}
		})
		$("#drop_logout").on("click",function(){
			window.location.href = "includes/logout.php";
		})
		$("#drop_login").on("click",function(){
			window.location.href = "login/";
		})
		$(".dialog_load_spot").on("click", "#close_button_dg", function(){
			$(".dialog").toggle("drop", {direction: "down"}, 300);
			$(".dialog_bg").fadeOut(200)
		})
		// we need to add $_GET['user'] back to this eventually
		$(".col2").load("includes/sections/browser.php");
		$(".footer_load").load("includes/sections/dynamic/song_info.php");

		$(".wrapper").on('click', '.song_row', function(){
			var isCol3Visible = $(".col3").is(":visible");
			var trackID = this.id

			if(isCol3Visible) {
				$('#col3_wrapper').toggle("drop", {direction: "right"}, 300)
				$('.col3').toggle("drop", {direction: "right"}, 300, function(){
					$(".col3").empty()
					$(".col3").load("includes/sections/selected_info.php?" + $.param({ID: trackID }), function(){
						$('#col3_wrapper').toggle("drop", {direction: "right"}, 300)
						$('.col3').toggle("drop", {direction: "right"}, 300);
					});
				});
			} else {
				$(".col3").empty()
				$(".col3").load("includes/sections/selected_info.php?" + $.param({ID: trackID }), function(){
					$('#col3_wrapper').toggle("drop", {direction: "right"}, 300)
					$('.col3').toggle("drop", {direction: "right"}, 300);
				});
			}
		});

		//$("#someSelector").one("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function(){ ... });

		setInterval(function(){
			$.get('includes/sections/dynamic/simple/trackid.php', function(data){
				if(!oldTrackID){
					oldTrackID = data;
				}
				if(oldTrackID != data){
					$(".footer_load").fadeOut(100, function(){
						$(".footer_load").load("includes/sections/dynamic/song_info.php", function(){
							$(".footer_load").fadeIn(100);
						});
					})
				}
				oldTrackID = data;
			})
		}, 5000)

		setInterval(function(){
			$.get('includes/sections/dynamic/new_song.php', function(data){
				$(".song_list").append(data);
			})
		}, 1000)

		setInterval(function(){
			$(".pb_load").load("includes/sections/dynamic/ffmpeg_pb.php")
		}, 1000)

		$("#library").on("click",function(){
			$('.col1').toggle("drop", {direction: "left"}, 300);
			$(".col2").fadeOut(100,function(){
				$(".col2").empty();
				$(".col2").load("includes/sections/browser.php", function(){
					$(".col2").fadeIn(100);
				})
			})
		})
		$("#history").on("click",function(){
			$('.col1').toggle("drop", {direction: "left"}, 300);
			$(".col2").fadeOut(100,function(){
				$(".col2").empty();
				$(".col2").load("includes/sections/history.php", function(){
					$(".col2").fadeIn(100);
				})
			})
		})
		$("#queue").on("click",function(){
			$('.col1').toggle("drop", {direction: "left"}, 300);
			$(".col2").fadeOut(100,function(){
				$(".col2").empty();
				$(".col2").load("includes/sections/queue.php", function(){
					$(".col2").fadeIn(100);
				})
			})
		})
		$("#myitems").on("click",function(){
			$('.col1').toggle("drop", {direction: "left"}, 300);
			$(".col2").fadeOut(100,function(){
				$(".col2").empty();
				$(".col2").load("includes/sections/browser.php?" + $.param({user: usern}), function(){
					$(".col2").fadeIn(100);
				})
			})
		})
		$("#userlist").on("click",function(){
			$('.col1').toggle("drop", {direction: "left"}, 300);
			$(".col2").fadeOut(100,function(){
				$(".col2").empty();
				$(".col2").load("includes/sections/userlist.php", function(){
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
		<div class="col3">
		</div>
	</div>
</body>

</html>