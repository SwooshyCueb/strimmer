<?php

function soundcloud_resolveFromURL($track_url) {
	if(isset($track_url)) {
		include dirname(dirname(__FILE__)) . "/settings.php";

		$url = "http://api.soundcloud.com/resolve.json?url=" . $track_url . "&client_id=" . $sc_api_key;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   

		$output = curl_exec($curl);
		curl_close($curl);

		//echo "<pre>" . var_export($output,true) . "</pre><br/><br/>";
		return $output;
	}
}

function soundcloud_getStreamVars($location) {
	if(isset($location)) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $location);
		curl_setopt($curl, CURLOPT_HEADER, 0);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   

		$output = curl_exec($curl);
		curl_close($curl);

		//echo "<pre>" . var_export($output,true) . "</pre><br/><br/>";
		return $output;
	}
}

function soundcloud_getDirectStream($location) {
	if(isset($location)) {
		include dirname(dirname(__FILE__)) . "/settings.php";

		echo '<span style="color: #090;">' . $location . "?client_id=" . $sc_api_key . '</span>';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $location . "?client_id=" . $sc_api_key);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($curl, CURLOPT_HEADER, true);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0");

		$output = curl_exec($curl);
		echo '<span style="color: #f00;">' . $output . '</span>';
		preg_match_all('/^Location:(.*)$/mi', $output, $matches);

		curl_close($curl);

		return $matches;
	}
}