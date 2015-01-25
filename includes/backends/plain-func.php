<?php

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