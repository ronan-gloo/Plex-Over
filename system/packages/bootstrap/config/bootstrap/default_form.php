<?php

return array(
	
	'open' => array(
		'attrs' => array('type' => array('inline', 'vertical', 'search', 'horizontal'))	
	),
	'fieldset_line_open' => array(
		'attrs' => array('state' => array('error', 'warning', 'success'))
	),
	'help' => array(
		'attrs' 	=> array('type' => array('inline', 'block', ''))
	),
	'input' => array(
		'attrs' => array(
			'prepend' => null,
			'append'	=> null,
			'size'		=> array('mini', 'small', 'medium'),
			'state' 	=> array('disabled', 'active')
		)
	),
	'button' => array(
		'attrs' => array(
			'icon'		=> null,
			'icon-pos'=> array('left', 'right'),
			'status'		=> array('primary', 'inverse', 'default', 'warning', 'danger', 'info', 'success'),
			'size'		=> array('small', 'mini', 'large', 'xlarge'),
			'state' 	=> array('disabled', 'active')
		)
	),
	'typeahead' => array(
		'attrs' => array('source' => null, 'items' => null, 'multiple' => null)
	),
	'checkbox_radio' => array(
		'attrs' => array('label' => null, 'type' => 'inline')
	)
);