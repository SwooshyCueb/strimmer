<?php
include dirname(dirname(__FILE__)) . "/settings.php";

function hypem_getInfo($url) {
	include dirname(dirname(__FILE__)) . "/settings.php";

	// We need this cookie to fetch track source info
	$cookie = [];
	preg_match("/AUTH=[^;]+/", get_headers($url, 1)["Set-Cookie"], $cookie);
	$cookie = $cookie[0];

	/* Sometimes a session variable is sent along with post data, but we don't need it.
	$session = [];
	preg_match("/AUTH=[0-9]+%3A([a-z0-9]+)%3A/", $cookie, $session);
	$session = $session[1];
	*/

	// We have to login because guests can only load a certain number of pages before
	// the JSON data we need from the page stops appearing.
	$postdata = 'act=' . urlencode("login") . '&' .
		//'session=' . urlencode($session) . '&' .
		'user_screen_name=' . urlencode($hypem['user']) . '&' .
		'user_password=' . urlencode($hypem['pass']);

	// So let's log in.
	$hypem_curl = curl_init();

	curl_setopt($hypem_curl, CURLOPT_URL, "https://hypem.com/inc/user_action");
	//curl_setopt($hypem_curl, CURLOPT_COOKIE, $cookie);
	curl_setopt($hypem_curl, CURLOPT_HEADER, false);
	curl_setopt($hypem_curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($hypem_curl, CURLOPT_POST, 4);
	curl_setopt($hypem_curl, CURLOPT_POSTFIELDS, $postdata);

	$login_result = json_decode(curl_exec($hypem_curl));

	curl_close($hypem_curl);

	// And now we fetch the page.
	$hypem_curl = curl_init();

	curl_setopt($hypem_curl, CURLOPT_URL, $url);
	curl_setopt($hypem_curl, CURLOPT_COOKIE, $cookie);
	curl_setopt($hypem_curl, CURLOPT_HEADER, false);
	curl_setopt($hypem_curl, CURLOPT_RETURNTRANSFER, true);

	$trackpage_str = curl_exec($hypem_curl);

	curl_close($hypem_curl);

	// And now we fetch the contents of the JSON script block
	$trackpage = new DomDocument;

	// Don't display warnings.
	$errlvl = error_reporting();
	error_reporting($errlvl & ~E_WARNING);

	$trackpage->validateOnParse = true;
	$trackpage->loadHTML($trackpage_str);

	$trackdata = $trackpage->getElementById("displayList-data")->textContent;

	error_reporting($errlvl);

	$trackdata = json_decode($trackdata, true);

	// And here we actually get the link to the audio file.
	$hypem_curl = curl_init();

	curl_setopt($hypem_curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($hypem_curl, CURLOPT_URL, "http://hypem.com/serve/source/" . $trackdata["tracks"][0]["id"] . '/' . $trackdata["tracks"][0]["key"]);
	curl_setopt($hypem_curl, CURLOPT_COOKIE, $cookie);
	curl_setopt($hypem_curl, CURLOPT_HEADER, false);

	$tracksource = curl_exec($hypem_curl);
	$tracksource = json_decode($tracksource, true);

	curl_close($hypem_curl);

	// Let's put together our return data
	$ret["id"] = $trackdata["tracks"][0]["id"];
	$ret["length"] = $trackdata["tracks"][0]["time"];
	$ret["title"] = $trackdata["tracks"][0]["song"];
	$ret["artist"] = $trackdata["tracks"][0]["artist"];
	$ret["url"] = str_replace(" ", "%20", $tracksource["url"]);

	return $ret;
}


?>