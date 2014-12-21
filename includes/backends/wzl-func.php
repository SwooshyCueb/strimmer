<?php

function wzl_subIDFromURL($sub_url) {
	if(isset($sub_url)) {
		if (preg_match("/\/([0-9]+)/", $sub_url, $matches)) {
			return $matches[1];
		} else {
			return FALSE;
		}
	}
}

function wzl_resolveFromID($sub_id) {
	if(isset($sub_id)) {
		$url = "https://www.weasyl.com/api/submissions/" . $sub_id . "/view";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$output = curl_exec($curl);
		curl_close($curl);

		return $output;
	}
}

function wzl_isUseableSubmission($data) {
	if(isset($data)) {
		if (isset($data['error'])) {
			// Probably an invalid or mature submission
			// TODO: Let users login for mature submissions
			return FALSE;
		} elseif ($data['subtype'] !== "multimedia") {
			// Definitely not music.
			return FALSE;
		} elseif ($data["media"]["submission"][0]["url"] == NULL) {
			// Embedded material.
			// TODO: Send embedded soundcloud material over to soundcloud.php
			return FALSE;
		} elseif (substr($data["media"]["submission"][0]["url"], -3) == "swf") {
			// Not a music submission
			return FALSE;
		} else {
			return TRUE;
		}
	}
}

function wzl_getAlbumArt($data) {
	if(isset($data['media']['cover'][0]['url'])) {
		return $data['media']['cover'][0]['url'];
	} elseif(isset($data['media']['thumbnail'][0]['url'])) {
		return $data['media']['thumbnail'][0]['url'];
	} elseif(isset($data['owner_media']['avatar'][0]['url'])) {
		return $data['owner_media']['avatar'][0]['url'];
	} else {
		return NULL;
	}
}

?>