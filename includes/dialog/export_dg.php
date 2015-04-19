<div class="dialog">
	<form action="includes/export.php" method="GET" id="json">
		<input type="hidden" name="type" value="json">
		<span class="button" onClick="document.getElementById('json').submit();">JSON</span>
	</form>
	<br/><br/>
	<form action="includes/export.php" method="GET" id="jsonp">
		<input type="hidden" name="type" value="json">
		<input type="hidden" name="pretty" value="1">
		<span class="button" onClick="document.getElementById('jsonp').submit();">Pretty JSON</span>
	</form>
	<br/><br/>
	<form action="includes/export.php" method="GET" id="csv">
		<input type="hidden" name="type" value="csv">
		<span class="button" onClick="document.getElementById('csv').submit();">CSV</span>
	</form>
	<br/><br/>
	<form action="includes/export.php" method="GET" id="php">
		<span class="button" onClick="document.getElementById('php').submit();">PHP Array</span>
	</form>
	<br/><br/><br/><br/>
	<span class="button" id="close_button_dg">Cancel</span>
</div>
<div class="dialog_bg"></div>