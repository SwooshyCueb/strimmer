<?php

function jamendo_resolveFromID($track_id) {
	if(isset($track_id)) {
		include dirname(dirname(__FILE__)) . "/settings.php";

		$url = "http://api.jamendo.com/v3.0/tracks/?client_id=" . $jm_api_key . "&format=json&limit=1&id=" . $track_id . "&audioformat=mp32&audiodlformat=flac";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   

		$output = curl_exec($curl);
		curl_close($curl);

		return $output;
	}
}