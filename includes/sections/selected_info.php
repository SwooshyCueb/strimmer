<?php
if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include dirname(dirname(__FILE__)) . "/functions.php";

$query = 'SELECT * FROM db_cache WHERE TRACKID="' . $_GET['ID'] . '" LIMIT 1';
$result = mysqli_query($mysqli,$query);
if(!mysqli_num_rows($result)) {
	die("Error in request.");
}
$row = mysqli_fetch_array($result);

$filename = dirname(dirname(dirname(__FILE__))) . "/" . "cache/" . $row['TRACKID'] . ".jpg";
//$filename = dirname(__FILE__) . "/black_test.jpg";
if(is_file($filename)) {
	$image = new Imagick($filename);
	$image->setImageAlphaChannel(Imagick::ALPHACHANNEL_DEACTIVATE);
	$image->quantizeImage(6,Imagick::COLORSPACE_RGB,0,false,false);
	$image->gammaImage(3);
	$pixels = $image->getImageHistogram();

	$hex = array();
	$counts = array();

	foreach ($pixels as $pixel) {
		$i++;
		$hex[$i] = "";

		$pixel_RGB = $pixel->getColor();

		foreach ($pixel_RGB as $pixel_channel) {
			// exclude alpha channel
			if(strlen($hex[$i]) < 6) {
				$hex[$i] .= substr("00" . dechex($pixel_channel), -2);
			}
		}
		$counts[$i] = $pixel->getColorCount();
		arsort($counts);
	}
	$image->clear();
	$link_color = "#" . $hex[key($counts)];
	asort($counts);
	$secondary_color = "#" . $hex[key($counts)];
	// echo key($counts);
	// echo("<pre>" . var_export($hex,true) . "</pre>");
	// echo("<pre>" . var_export($counts,true) . "</pre>");
}

?>

<div class="col3_bg"><img src="cache/<?php echo $row['TRACKID']; ?>.jpg"/></div>
<div class="col_wrapper" id="col3_wrapper">
	<div class="selected_info_art"><img src="cache/<?php echo $row['TRACKID']; ?>.jpg"/></div>
	<div class="selected_info_title"><?php echo $row['RETURN_ARG2']; ?></div>
	<div class="selected_info_artist"><a style="color: <?php echo $link_color; ?>;" href="<?php echo $row['RETURN_ARG4']; ?>"><?php echo $row['RETURN_ARG3']; ?></a></div>
	<table class="selected_info_various">
		<tr>
			<td style="color: <?php echo $secondary_color; ?>;">Service</td>
			<td><?php echo getLongService($row['SERVICE']); ?></td>
		</tr>
		<tr>
			<td style="color: <?php echo $secondary_color; ?>;">Track ID</td>
			<td><?php echo $row['TRACKID']; ?></td>
		</tr>
	</table>
	<table class="selected_info_various">
		<tr>
			<td style="color: <?php echo $secondary_color; ?>;">Permalink</td>
			<td><a style="color: <?php echo $link_color; ?>;" href="<?php echo $row['RETURN_ARG6']; ?>"><?php echo $row['RETURN_ARG6']; ?></a></td>
		</tr>
		<tr>
			<td style="color: <?php echo $secondary_color; ?>;">API Link</td>
			<td><a style="color: <?php echo $link_color; ?>;" href="<?php echo $row['RETURN_ARG5']; ?>"><?php echo $row['RETURN_ARG5']; ?></a></td>
		</tr>
		<tr>
			<td style="color: <?php echo $secondary_color; ?>;">Owner</td>
			<td><a style="color: <?php echo $link_color; ?>;" href="<?php echo $row['RETURN_ARG4']; ?>"><?php echo $row['RETURN_ARG3']; ?></a></td>
		</tr>
	</table>
	<table class="selected_info_various">
		<tr>
			<td style="color: <?php echo $secondary_color; ?>;">Artwork</td>
			<td><a style="color: <?php echo $link_color; ?>;" href="<?php echo $row['RETURN_ARG7']; ?>"><?php echo $row['RETURN_ARG7']; ?></a></td>
		</tr>
	</table>
	<table class="selected_info_various">
		<tr>
			<td style="color: <?php echo $secondary_color; ?>;">Added On</td>
			<td><?php echo date('M. d, Y g:i A',$row['ADDED_ON']); ?></td>
		</tr>
		<tr>
			<td style="color: <?php echo $secondary_color; ?>;">Added By</td>
			<td><?php echo $row['ADDED_BY']; ?></td>
		</tr>
	</table>
	<div class="col3_closer"><span class="oi" data-glyph="x"></span></div>
</div>

<script>
$(document).ready(function(){
	$(".col3_closer").on("click",function(){
		$('.col3').toggle("drop", {direction: "right"}, 300, function(){
			$(this).fadeOut(100);
			$(".col3").empty()
		});
	})
});
</script>