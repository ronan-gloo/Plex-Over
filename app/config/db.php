<?php
/**
 * Base Database Config.
 *
 * See the individual environment DB configs for specific config information.
 */

return array(
	
	'active' => 'library',
	
	'library' => array(
		'type'        => 'sqlite',
		'connection'  => array('persistent' => true),
		'identifier'   => '`',
		'table_prefix' => '',
		'charset'      => 'utf8',
		'enable_cache' => true,
		'profiling'    => false,
		'connection'  => array(
			'dsn'        => 'sqlite:'.DBPATH.'com.plexapp.plugins.library.db',
			'username'   => '',
			'password'   => '',
	))

);
