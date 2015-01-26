<?php

include dirname(dirname(__FILE__)) . "/settings.php";

function getFirstPlayableStream($streaminfo)
{
	foreach ($streaminfo as $stream)
	{
		if ($stream["codec_type"] === "audio")
		{
			return $stream;
		}
	}
	return False;
}

function getFormatInfo($url) {
	include dirname(dirname(__FILE__)) . "/settings.php";
	$ffprobeoutarr = [];
	exec($icecast['ffprobe'] . ' -hide_banner -show_format \'' . $url . '\'', $ffprobeoutarr);
	$format = [];
	$informatblock = False;
	foreach ($ffprobeoutarr as $line)
	{
		if ($informatblock)
		{
			if ($line === "[/FORMAT]")
			{
				return $format;
			} else {
				$data = explode("=", $line, 2);
				if (strpos($data[0], ':') !== FALSE)
				{
					$data0 = explode(":", $data[0], 2);
					$format[$data0[0]][$data0[1]] = $data[1];
				} else {
					$format[$data[0]] = $data[1];
				}
			}
		} else {
			if ($line === "[FORMAT]")
			{
				$informatblock = True;
			}
		}
	}
	return False;
}

function getTitle($formatinfo)
{
	return $formatinfo["TAG"]["title"];
}

function getArtist($formatinfo)
{
	return $formatinfo["TAG"]["artist"];
}

function getAlbum($formatinfo)
{
	return $formatinfo["TAG"]["album"];
}

//function getDate($formatinfo)
//{
//	return $formatinfo["TAG"]["date"];
//}

function getBitrate($formatinfo)
{
	return $formatinfo["bit_rate"];
}

function getLength($formatinfo)
{
	return $formatinfo["duration"];
}

function generateID($formatinfo)
{
	$formatstring = json_encode($formatinfo);
	$ID = "UNDF" . dechex(crc32($formatstring));
	return $ID;
}

function dumpAlbumArt($url, $ID)
{
	include dirname(dirname(__FILE__)) . "/settings.php";
	if (!file_exists(dirname(dirname(dirname(__FILE__))) . "/cache/" . $ID . '.jpg'))
	{
		if (!file_exists(dirname(dirname(dirname(__FILE__))) . "/cache"))
		{
			mkdir(dirname(dirname(dirname(__FILE__))) . "/cache", 0765, true);
			// For whatever reason, imagemagick can't write images to this folder
			// if we don't have exec perms
		}
		if (!file_exists(dirname(dirname(dirname(__FILE__))) . "/cache/plain"))
		{
			mkdir(dirname(dirname(dirname(__FILE__))) . "/cache/plain", 0765, true);
		}
		$retval = 0;
		$toss = [];
		exec($icecast['ffmpeg'] . ' -hide_banner -i \'' . $url . '\' -an -vcodec copy \'' . dirname(dirname(dirname(__FILE__))) . "/cache/plain/" . $ID . '.jpg\'', $toss, $retval);
		if ($retval)
		{
			return False;
		}
		$image = new Imagick();
		$image->readImage(dirname(dirname(dirname(__FILE__))) . "/cache/plain/" . $ID . '.jpg');
		$image->setFormat("jpg");
		$image->setImageCompression(Imagick::COMPRESSION_JPEG);
		$image->setImageCompressionQuality(97);
		$image->thumbnailImage(100,100);
		$image->writeImage(dirname(dirname(dirname(__FILE__))) . "/cache/" . $ID . '.jpg');
		$image->clear();
		unlink(dirname(dirname(dirname(__FILE__))) . "/cache/plain/" . $ID . '.jpg');
	}
	return True;
}

?>