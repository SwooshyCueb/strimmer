<?php

include_once dirname(__FILE__) . "/settings.php";

// SOUNDCLOUD FUNCTIONS
function soundcloud_resolveFromURL($track_url) {
	if(isset($track_url)) {
		$client_id = "SOMEKEY";
		$url = "http://api.soundcloud.com/resolve.json?url=" . $track_url . "&client_id=" . $client_id;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   

		$output = curl_exec($curl);
		curl_close($curl);

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

		return $output;
	}
}

function soundcloud_getDirectStream($location) {
	if(isset($location)) {
		$client_id = "SOMEKEY";
		echo '<span style="color: #090;">' . $location . "?client_id=" . $client_id . '</span>';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $location . "?client_id=" . $client_id);
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

function mpd_getPlaylist() {
	$mpd['host'] = "theblackparrot.us";
	$mpd['port'] = 6600;
	$mpd['password'] = "SOMEPASS";
	$playlist = explode("\n",str_replace("\n\r","\n",shell_exec("mpc -f \"%file%\" -h " . $mpd['host'] . " -p " . $mpd['port'] . " playlist")));

	return $playlist;
}

//
// --= ALL LIST ROWS WILL BE DRAWN BY THIS FUNCTION =--
//
function getListRow_Service($row) {
	switch ($row['SERVICE']) {
		case 'SDCL':
			echo '<tr class="song_row">';
				echo '<td>';
					echo '<img src="' . $row['RETURN_ARG7'] . '" class="list_art"/>';
					echo '<div class="list_info">';
						echo '<a href="includes/delete_song.php?id=' . $row['ID'] . '&user=' . $_SESSION['username'] . '"/><span class="oi" data-glyph="delete" id="list_delete"></span></a>';
						echo '<span class="list_title">' . $row['RETURN_ARG2'] . '</span><br/>';
						echo '<span class="list_artist"><a href="' . $row['RETURN_ARG4'] . '">' . $row['RETURN_ARG3'] . '</a></span>';
					echo '</div>';
				echo '</td>';
				echo '<td>' . $row['ADDED_BY'] . '</td>';
				echo '<td>' . date('M. d, Y g:i A',$row['ADDED_ON']) . '</td>';
			echo '</tr>';
			break;
		
		default:
			break;
	}
}