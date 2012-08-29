
// Add css class when player toogled in fullscreen
var switchFullScreenClass = function(e) {
	if (this.isFullScreen) {
		$('#'+this.id).addClass('fullscreen');
	}
	else {
		$('#'+this.id).removeClass('fullscreen');
	}
}

$(function() {
	var mvideos = $('.video-js');
	// Set videos events
	mvideos.each(function(){
		var vplayer = _V_($(this).attr('id'));
		vplayer.addEvent('fullscreenchange', switchFullScreenClass);
	});
	
	var controlsTimer = null;
	$('.video-js').on('mousemove', function(){
		var $this = $(this);
		if (controlsTimer) {
			clearTimeout(controlsTimer);
			$this.removeClass('hide-controls');
		}
		controlsTimer = setTimeout(function(){
			$this.addClass('hide-controls');
		}, 2000);
	});
	
});
