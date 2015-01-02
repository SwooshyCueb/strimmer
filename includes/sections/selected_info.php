<?php
if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include dirname(dirname(__FILE__)) . "/functions.php";

$query = "SELECT * FROM db_cache WHERE PLAYING=1 LIMIT 1";
$result = mysqli_query($mysqli,$query);
$row = mysqli_fetch_array($result);

$filename = dirname(dirname(dirname(__FILE__))) . "/" . "cache/" . $row['TRACKID'] . ".jpg";
//$filename = dirname(__FILE__) . "/black_test.jpg";
if(is_file($filename)) {
	$image = new Imagick($filename);
	$image->setImageAlphaChannel(Imagick::ALPHACHANNEL_DEACTIVATE);
	$image->quantizeImage(8,Imagick::COLORSPACE_RGB,0,true,false);
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
</div>