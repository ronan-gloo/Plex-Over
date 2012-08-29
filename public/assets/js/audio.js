AudioPlayer = {};
AudioPlayer.init = function(){
	self = this;
	this.sm								= soundManager;
	this.sm.url						= '/assets/swf/';
	this.sm.debugMode			= false;
	this.sm.debugFlash		= false;
	//this.sm.flashVersion 	= 9;
	this.sm.useHighPerformance = false;
	//this.sm.preferFlash = false;
	//this.sm.useHTML5Audio = true;
	
	this.player = $('.audio-player .audio');
	this.items	= $('[data-audio-url]');	
	this.item		= this.items.first();
	this.sound	= null;
	this.id			= null;
	
	// Set data index
	this.items.each(function(i, v){ $(this).data('id', i) });
	// Text for the first element
	this.ui.text();
	
	// Use the play buton
	this.items.click(function(e){
		if (self.id !== $(this).data('id')) {
			self.actions.reload(this);
		}
		else {
			self.actions.play();
		}
	});
	this.player.children('.control').click(function(){
		self.actions.play();
	});

	// Seek on progress element
	this.player.find('.progress').live('click', function(e){
		var pX = e.clientX - $(this).offset().left;
		var ratio = self.sound.duration / $(this).width();
		self.sound.setPosition((pX * ratio).toFixed());
	});
	
}

AudioPlayer.actions = {
	load: function(){
		self.sound = self.sm.createSound({
			id: 'audio_'+self.id,
			autoLoad: true,
			autoPlay: true,
			url: self.item.attr('data-audio-url'),
			whileloading: function() {
				var perc = (this.bytesLoaded / this.bytesTotal) * 100;
				self.ui.draw(perc, 'load');
			},
			whileplaying: function() {
				var perc = (this.position / this.duration) * 100;
				self.ui.draw(perc, 'play');
				self.ui.timer(this.position);
			},
			onfinish: function() {
				self.actions.playNext();
			}
		});
		return self.sound;
	},
	reload: function(el) {
		this.destruct();
		self.item = $(el);
		self.id		= self.item.data('id');
		self.ui.text();
		this.play();
	},
	play: function(){
		if (! self.sound) {
			self.sound = self.actions.load();
			return self.ui.resume();
		}
		if (self.sound.paused) {
			self.sound.resume();
			self.ui.resume();
		}
		else {
			self.sound.pause();
			self.ui.pause();
		}
	},
	playNext: function(){
		self.actions.destruct();
		if (self.items[self.id+1]) {
			self.actions.reload(self.items[self.id+1]);
		}
	},
	destruct: function(){
		if (! self.sound) return false;
		self.ui.destruct();
		self.sound.stop();
		self.sound.destruct();
		self.sound = null;
	}
}

AudioPlayer.ui = {
	// Update the progression
	draw: function(value, tag) {
		self.player.find('.'+tag).css('width', value+'%');
	},
	pause: function(){
		self.player.removeClass('playing');
		self.item.removeClass('playing');
	},
	resume: function(){
		self.player.addClass('playing');
		self.item.addClass('playing');
	},
	text: function(){
		self.player.find('.title').children('a').text(self.item.next('td').text());
	},
	// Update the timer
	timer: function(nMSec) {
	  var nSec = Math.floor(nMSec/1000),
	      min = Math.floor(nSec/60),
	      sec = nSec-(min*60),
	      str = min+':'+(sec<10?'0'+sec:sec);
	  self.player.find('.timeline').children('a').text(str);
	},
	destruct: function(){
		this.pause();
		self.player.find('.load, .play').width(0);
		self.player.find('.timeline').children('a').text('0:00');
	}
}

$(function() {
	AudioPlayer.init();
});

