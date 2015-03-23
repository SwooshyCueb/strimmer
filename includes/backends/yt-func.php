<?php
function getYouTubeData($url,$what) {
	switch($what) {
		case 'VideoID':
			$data = exec('youtube-dl --restrict-filenames --get-id \'' . $url . '\'');
			break;

		case 'StreamLink':
			$data = exec('youtube-dl --youtube-skip-dash-manifest -g -f mp3/aac/m4a \'' . $url . '\'');
			break;

		case 'JSON':
			$data = exec('youtube-dl --restrict-filenames -j \'' . $url . '\'');
			break;
		
		default:
			return -1;
			break;
	}

	$data = str_replace("\r","",$data);
	$data = str_replace("\n","",$data);
	return $data;
}