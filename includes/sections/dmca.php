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
				<h1>DMCA/Removal Request Information</h1>
				<p>I completely understand if you wish to have your track removed from Strimmer. Let us know at strimmer@theblackparrot.us if you would like your track removed, and I will remove it ASAP.</p>
				<p>DMCA'd tracks will be promptly removed and added to the blacklist. The blacklist will reside in an SQLite database that can be pushed up alongside the code to prevent others from adding the tracks on their own Strimmer application.<br/>Strimmer is not responsible for users removing the database, although we do try to prevent that from happening. [todo]</p>
				<p>If you are hosting your own version of Strimmer and you receive a DMCA request, let us know at strimmer@theblackparrot.us and we will add it to the primary database.</p>
				<h2>Blacklist</h2>
				<table class="dmca-table">
					<tr>
						<td>Artist</td>
						<td>Track</td>
						<td>Affected Track ID</td>
						<td>DMCA Date</td>
						<td>Submitted by</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>