<?php

function debug($s, $d = false) {
	$d === true ? var_dump($s) : print_r($s);
	exit();
}

// Load in the Autoloader
require COREPATH.'classes'.DIRECTORY_SEPARATOR.'autoloader.php';
class_alias('Fuel\\Core\\Autoloader', 'Autoloader');

// Bootstrap the framework DO NOT edit this
require COREPATH.'bootstrap.php';

Autoloader::add_classes(array(
	// app classes
	'Model'				=> APPPATH.'classes/model.php',
	'Controller'	=> APPPATH.'classes/controller.php',
	'View'				=> APPPATH.'classes/view.php',
	'Html'				=> APPPATH.'classes/html.php',
	'Str'					=> APPPATH.'classes/str.php',
	
	// Specific sqlite connection
	'Database_Sqlite_Connection' => APPPATH.'classes/database/sqlite/connection.php',
	
	// Read Only query catcher
	'Database_Query' => APPPATH.'classes/database/query.php',
	
	'Num' => APPPATH.'classes/num.php',
	'PlexOverException' => APPPATH.'classes/exceptions.php',
));

// Register the autoloader
Autoloader::register();

/**
 * Your environment.  Can be set to any of the following:
 *
 * Fuel::DEVELOPMENT
 * Fuel::TEST
 * Fuel::STAGE
 * Fuel::PRODUCTION
 */
Fuel::$env = (isset($_SERVER['FUEL_ENV']) ? $_SERVER['FUEL_ENV'] : Fuel::DEVELOPMENT);

// Initialize the framework with the config file.
Fuel::init('config.php');

// LOad min config file
Config::load('main', true);

// Appname
define('APPNAME', Config::get('main.appname'));

/**
 * The path to the database files.
 */
define('DBPATH', Config::get('main.databases').DIRECTORY_SEPARATOR);

// Version
define('APPVERS', '0.1 Alpha');
