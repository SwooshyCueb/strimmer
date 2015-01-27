<?php
if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include dirname(dirname(dirname(__FILE__))) . "/settings.php";
include_once dirname(dirname(dirname(__FILE__))) . "/session.php";
include dirname(dirname(dirname(__FILE__))) . "/functions.php";

$query = "SELECT * FROM db_cache WHERE PLAYING=1 LIMIT 1";
$result = mysqli_query($mysqli,$query);
$row = mysqli_fetch_array($result);

$query = "SELECT ADDED_BY FROM play_history ORDER BY PLAYED_ON DESC LIMIT 1";
$result = mysqli_query($mysqli,$query);
$queue = mysqli_fetch_array($result);

?>

<img src="cache/<?php echo $row['TRACKID']; ?>.jpg" class="np_art"/>
<span class="np_title"><?php echo $row['RETURN_ARG2']; ?></span><br/>
<span class="np_artist" line="added_by"></span><br/>
<!--<span class="np_who">Added by <?php echo $row['ADDED_BY']; ?></span>-->

<script>
	<?php
		echo 'var artist = "' . $row['RETURN_ARG3'] . '";';
		echo 'var owner = "' . $row['RETURN_ARG4'] . '";';
		echo 'var added_by = "' . $row['ADDED_BY'] . '";';
		echo 'var queued_by = "' . $queue['ADDED_BY'] . '";';
	?>

	var delay = 7500;

	if(!queued_by || queued_by == "") {
		queued_by = "Strimmer"
	}

	function toggleMinorLine() {
		var line = $(".np_artist").attr("line");

		if(line == "artist") {
			$(".np_artist").attr("line","added_by")
			$(".np_artist").html('added by ' + added_by)
		}
		if(line == "added_by") {
			$(".np_artist").attr("line","queued_by")
			$(".np_artist").html('by <a href="' + owner + '">' + artist + '</a>')
		}
		if(line == "queued_by") {
			$(".np_artist").attr("line","artist")
			$(".np_artist").html('queued by ' + queued_by)
		}
	}

	function toggleFooterInfo() {
		if($(".np_artist").hasClass("fadein_full")) {
			$(".np_artist").removeClass("fadein_full")
			$(".np_artist").addClass("fadeout_full")
			$(".np_artist").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
				toggleMinorLine()
				$(".np_artist").removeClass("fadeout_full")
				$(".np_artist").addClass("fadein_full")
			})
		} else {
				toggleMinorLine()
				$(".np_artist").removeClass("fadeout_full")
				$(".np_artist").addClass("fadein_full")
		}
	}
	if(info_timer) {
		clearInterval(info_timer);
	}
	info_timer = setInterval(toggleFooterInfo,delay)
	toggleFooterInfo();
</script>