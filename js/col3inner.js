function queue_track(trackID, qmode, element){
	$(element).attr('id','col3b_disabled');
	$.get("includes/queue_song.php?" + $.param({ID: trackID, mode: qmode}), function(){
		switch(qmode) {
			case "queue":
				$(element).attr('onclick',"queue_track('" + trackID + "', 'unqueue', this);");
				$(element).attr('id','col3b_red');
				$(element).html('<span class="oi" data-glyph="circle-x"></span>Unqueue');
				break;

			case "unqueue":
				$(element).attr('onclick',"queue_track('" + trackID + "', 'queue', this);");
				$(element).attr('id','col3b_green');
				$(element).html('<span class="oi" data-glyph="pulse"></span>Queue');
				break;
		}
	})
}
function delete_track(trackID, element){
	var tID = trackID
	$(element).attr('id','col3b_disabled');
	$.get("includes/delete_song.php?" + $.param({id: trackID}), function(trackID){
		//$(".song_row[id='" + tID + "']").css("font-weight","bold")
		$(".song_row[id='" + tID + "']").toggleClass("song_row_deleteanim")
		$(".song_row[id='" + tID + "']").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
			$(".song_row[id='" + tID + "']").remove()
		})
		$('#col3_wrapper').toggle("drop", {direction: "right"}, 300)
		$('.col3').toggle("drop", {direction: "right"}, 300, function(){
			$(".col3_closer").fadeOut(100);
			$(".col3").empty()
		});
	})
}
$(document).ready(function(){
	$(".col3_closer").on("click",function(){
		//$('#col3_wrapper').toggle("drop", {direction: "right"}, 300)
		$('.col3').removeClass("col3_in")
		$('.col3').addClass("col3_out")
		$(".col3").one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
			//$(this).fadeOut(100);
			if($('.col3').hasClass("col3_out")) {
				$(".col3").empty()
				$('.col3').removeAttr("track")
			}
		});
	})
});