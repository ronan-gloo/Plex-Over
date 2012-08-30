<?php

return array(
	
	// App tab
	'global' => array(
		'title' 			=> 'Application',
		'appname'			=> 'Application name',
		'per_page'		=> 'Items per page',
		'language'		=> 'Language',
		'en' 	=> 'English',
		'fr'	=> 'French',
	),
	
	// Video Tab
	'video' => array(
		'title'							=> 'Videos',
		'subs_color'				=> 'Subtitles color',
		'm3u8_quality'			=> 'HLS streaming quality',
		'm3u8_quality_label'=> 'Safari only !',
		'm3u8_quality_help' => '1 to 10'
	),
	
	// Server tab
	'server' => array(
		'title' 			=> 'Media server',
		'host'				=> 'Hostname',
		'port'				=> 'Port number',
		'protocol'		=> 'Protocol',
		'identifier'	=> 'Machine identifier',
	),
	
	// flash messages
	'messages' => array(
		'success' => 'Parameters updated successfully',
		'error'		=> 'Something goes wrong, check write access for config/main.php'
	)
);