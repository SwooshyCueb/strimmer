<?php
if ((stripos(($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 'index.php') !== FALSE)) {
	header("Location: http://" . dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	exit;
}

include dirname(dirname(__FILE__)) . "/settings.php";
include_once dirname(dirname(__FILE__)) . "/session.php";
include dirname(dirname(__FILE__)) . "/functions.php";

?>
<!-- hooray, laziness -->
<div class="col_wrapper" id="userlist_wrapper">
	<div style="margin: auto; width: 100%; line-height: 24px;">
		<div class="stats_section_wrapper" style="width: 100%;">
			<div class="stats_section">
				<h1>Strimmer</h1>
				<p>Strimmer is a collaborative playlisting frontend and backend for an internet radio station. Sources are only pulled from cloud audio services, such as SoundCloud and Jamendo.<br/>Nothing is cached, except album art and track metadata, so as to not stress APIs.</p>
				<p>The project is free and open source under the Mozilla Public License v2.0, and is available at <a href="https://github.com/SwooshyCueb/strimmer">the project's GitHub page.</a> Do keep in mind this project is still under heavy development, so things may come and go as time goes on.</p>
				<p>(add more info later)</p>
			</div>
		</div>
	</div>
</div>