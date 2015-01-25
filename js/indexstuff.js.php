var oldTrackID;
<?php
	include dirname(dirname(__FILE__)) . "/includes/settings.php";

	if(isset($_SESSION['username'])) {
		echo 'var usern = "' . $_SESSION['username'] . '";';
	}
	echo 'var prog_title = "' . $prog_title . '";';
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
			toggleDialogAnim();
		})
	})
	$("#add_we").on("click",function(){
		$(".dialog_load_spot").empty()
		$(".dialog_load_spot").load("includes/dialog/weasyl.html", function() {
			toggleDialogAnim();
		})
	})
	$("#add_jm").on("click",function(){
		$(".dialog_load_spot").empty()
		$(".dialog_load_spot").load("includes/dialog/jamendo.html", function() {
			toggleDialogAnim();
		})
	})
	$("#drop_set").on("click",function(){
		$(".dialog_load_spot").empty()
		$(".dialog_load_spot").load("includes/dialog/settings.php", function() {
			toggleDialogAnim();
		})
	})
	$("#col1_toggle").on("click",function(){
		//$('.col1').toggle("drop", {direction: "left"}, 300);
		toggleCol1Anim();
	})
	$("#drop_logout").on("click",function(){
		window.location.href = "includes/logout.php";
	})
	$("#drop_login").on("click",function(){
		window.location.href = "login/";
	})
	$(".dialog_load_spot").on("click", "#close_button_dg", function(){
		toggleDialogAnim();
	})
	$(".dialog_load_spot").on("click", ".dialog_bg", function(){
		toggleDialogAnim();
	})
	// we need to add $_GET['user'] back to this eventually
	toggleCol2Anim(1)
	$(".col2").load("includes/sections/view.php?" + $.param({page: "default"}))
	$(".footer_load").load("includes/sections/dynamic/song_info.php");

	$(".wrapper").on('click', '.song_row', function(){
		//var isCol3Visible = $(".col3").is(":visible");
		var trackID = this.id

/*
		if($('.col1').hasClass("col1_in")) {
			$('.col1').removeClass("col1_in")
			$('.col1').addClass("col1_out")
		} else {
			$('.col1').removeClass("col1_out")
			$('.col1').addClass("col1_in")
		}
*/
		//if(isCol3Visible) {
		if($('.col3').hasClass("col3_in")) {
			//$('#col3_wrapper').toggle("drop", {direction: "right"}, 300)
			//$('#col3_wrapper').removeClass("col3_in")
			//$('#col3_wrapper').addClass("col3_out")
			$('.col3').removeClass("col3_in")
			$('.col3').addClass("col3_out")
			$(".col3").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
			//$('.col3').toggle("drop", {direction: "right"}, 300, function(){
				if($('.col3').hasClass("col3_out")) {
					$(".col3").empty()
					if ($('.col3').attr("track") === trackID) {
						$('.col3').removeAttr("track")
						} else {
						$(".col3").load("includes/sections/selected_info.php?" + $.param({ID: trackID }), function(){
							//$('#col3_wrapper').removeClass("col3_out")
							//$('#col3_wrapper').addClass("col3_in")
							$('.col3').removeClass("col3_out")
							$('.col3').addClass("col3_in")
							$('.col3').attr("track", trackID)
						});
					}
				}
			});
		} else {
			$(".col3").empty()
			$(".col3").load("includes/sections/selected_info.php?" + $.param({ID: trackID }), function(){
				//$('#col3_wrapper').removeClass("col3_out")
				//$('#col3_wrapper').addClass("col3_in")
				$('.col3').removeClass("col3_out")
				$('.col3').addClass("col3_in")
				$('.col3').attr("track", trackID)
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
				if(data) {
					$(".song_row[id='" + oldTrackID + "']").removeClass("playing")
					$(".song_row[id='" + data + "']").addClass("playing")
				}
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
		toggleCol1Anim()
		col3LeaveAnim()
		toggleCol2Anim()
		$(".col2").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
			$(".col2").empty();
			$(".col2").load("includes/sections/view.php?" + $.param({page: "default"}), function(){
				toggleCol2Anim(1);
				setWindowTitle("Library");
			})
		})
	})
	$("#history").on("click",function(){
		toggleCol1Anim()
		col3LeaveAnim()
		toggleCol2Anim()
		$(".col2").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
			$(".col2").empty();
			$(".col2").load("includes/sections/view.php?" + $.param({page: "history"}), function(){
				toggleCol2Anim(1);
				setWindowTitle("Play History");
			})
		})
	})
	$("#queue").on("click",function(){
		toggleCol1Anim()
		col3LeaveAnim()
		toggleCol2Anim()
		$(".col2").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
			$(".col2").empty();
			$(".col2").load("includes/sections/view.php?" + $.param({page: "queue"}), function(){
				toggleCol2Anim(1);
				setWindowTitle("Play Queue");
			})
		})
	})
	$("#myitems").on("click",function(){
		toggleCol1Anim()
		col3LeaveAnim()
		toggleCol2Anim()
		$(".col2").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
			$(".col2").empty();
			$(".col2").load("includes/sections/view.php?" + $.param({user: usern, page: "default"}), function(){
				toggleCol2Anim(1);
				setWindowTitle("My Items");
			})
		})
	})
	$("#userlist").on("click",function(){
		toggleCol1Anim()
		col3LeaveAnim()
		toggleCol2Anim()
		$(".col2").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
			$(".col2").empty();
			$(".col2").load("includes/sections/userlist.php", function(){
				toggleCol2Anim(1);
				setWindowTitle("Userlist");
			})
		})
	})
	$("#statistics").on("click",function(){
		toggleCol1Anim()
		col3LeaveAnim()
		toggleCol2Anim()
		$(".col2").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
			$(".col2").empty();
			$(".col2").load("includes/sections/statistics.php", function(){
				toggleCol2Anim(1);
				setWindowTitle("Statistics");
			})
		})
	})
});

function setWindowTitle(view) {
	document.title = prog_title + " - " + view;
}

function toggleCol1Anim() {
	if($('.col1').hasClass("col1_in")) {
		$('.col1').removeClass("col1_in")
		$('.col1').addClass("col1_out")
	} else {
		$('.col1').removeClass("col1_out")
		$('.col1').addClass("col1_in")
	}
}

function toggleCol2Anim(skip) {
	if($('.col2').hasClass("fadein_full")) {
		$('.col2').removeClass("fadein_full")
		$('.col2').addClass("fadeout_full")
	} else {
		if(skip != 1) {
			$('.col2').removeClass("fadeout_full")
			$('.col2').addClass("fadein_full")
		}
	}
	if(skip == 1) {
		$('.col2').removeClass("fadeout_full")
		$('.col2').addClass("fadein_full")
	}
}

function col3LeaveAnim() {
	if($('.col3').hasClass("col3_in")) {
		$('.col3').removeClass("col3_in")
		$('.col3').addClass("col3_out")
		$(".col3").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
			$(".col3").empty()
		})
	}
}

function toggleDialogAnim() {
	if($('.dialog').hasClass("dialog_in")) {
		$('.dialog').removeClass("dialog_in")
		$('.dialog').addClass("dialog_out")
		$('.dialog_bg').removeClass("fadein_half")
		$('.dialog_bg').addClass("fadeout_half")
		$(".dialog_bg").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
			$('.dialog_bg').css("z-index","-999")
			$('.dialog').css("z-index","-998")
		})
	} else {
		$('.dialog').removeClass("dialog_out")
		$('.dialog').addClass("dialog_in")
		$('.dialog_bg').removeClass("fadeout_half")
		$('.dialog_bg').addClass("fadein_half")
		$('.dialog_bg').css("z-index","399")
		$('.dialog').css("z-index","400")
	}
}