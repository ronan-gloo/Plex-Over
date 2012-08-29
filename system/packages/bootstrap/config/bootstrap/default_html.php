<?php

// Available color classes per component type

return array(
	'button' => array(
		'attrs' => array(
			'icon'		=> null,
			'icon-pos'=> array('left', 'right'),
			'status'	=> array('primary', 'inverse', 'default', 'warning', 'danger', 'info', 'success'),
			'size'		=> array('small', 'mini', 'large', 'xlarge'),
			'state' 	=> array('disabled', 'active')
		)
	),
	'progress' => array(
		'attrs' => array(
			'striped' => array(true, false),
			'active' 	=> array(true, false),
			'status'	=> array('info', 'success', 'warning', 'danger')
		)
	),
	'label_badge' => array(
		'attrs' => array(
			'status'	=> array('primary', 'inverse', 'default', 'warning', 'important', 'info', 'success')
		)
	),
	'alert' => array(
		'attrs' => array(
			'status' 	=> array('error', 'success', 'info'),
			'type'		=> array('block', 'inline'),
			'close'		=> array(true, false, 'fade')
		)
	),
	'modal' => array(
		'attrs' => array(
			'hide' 	=> array(true, false),
			'fade'	=> array(true, false)
		)
	),
	'dropdown' => array(
		'attrs' => array(
			'pull' => array('left', 'right'),
			'split'=> array(true)
		)
	),
	'tab' => array(
		'attrs' => array(
			'direction' => array('left', 'right', 'below'),
			'stacked'		=> array(true, false),
			'type'			=> array('tabs', 'pills'),
			'active'		=> array('*') // Set the activated tab
		)
	),
	'carousel' => array(
		'attrs' => array(
			'slide' 		=> array(true, false),
			'pause'			=> array(true, false),
			'interval' 	=> array('*'),
			'active'		=> array('*')
		)
	),
	'table' => array(
		'attrs' => array(
			'bordered' 	=> array(true, false),
			'striped'	 	=> array(true, false),
			'condensed'	=> array(true, false),
			'alternator'=> null,
			'language' 	=> null,
		)
	)
	
);