<?php

// Transcode parameters
return array(
	// Transcode public key
	'public_key' => 'KQMIY6GATPC63AIMC4R2',
	
	// Trancode private key (base 64 format'
	'private_key' => 'k3U6GLkZOoNIoSgjDshPErvqMIFdE0xMTx8kgsrhnC0=',
	
	// Path to image
	'image' => 'photo/:/transcode?',
	
	// Apple Htto Live Streaming
	'm3u8' => array(
		'url' => 'video/:/transcode/segmented/start.m3u8?identifier=com.plexapp.plugins.library&',
		'params' => array('offset'=> 0)
	),
	// Flash Streaming
	'flash' => array(
		'url'	=> 'video/:/transcode/generic.flv?format=flv&',
		'params'	=> array(
			//'videoCodec'				=> 'libx264',
			//'vpre'							=> 'video-embedded-h264',
			'videoBitrate'			=> '5000',
			//'audioCodec'				=> 'libfaac',
			//'apre'							=> 'audio-embedded-aac',
			//'audioBitrate'			=> '128',
			'size'							=> '640x480',
			//'fakeContentLength' => '2000000000'
		)
	)
	
);
