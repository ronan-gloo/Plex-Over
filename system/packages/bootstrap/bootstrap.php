<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */


Autoloader::add_core_namespace('Bootstrap');

Autoloader::add_classes(array(
	'Bootstrap\\Bootstrap'			=> __DIR__.'/classes/bootstrap.php',
	'Bootstrap\\Form_Instance'	=> __DIR__.'/classes/form/instance.php',
	'Bootstrap\\Form'						=> __DIR__.'/classes/form.php',
	'Bootstrap\\Html'						=> __DIR__.'/classes/html.php',
	'Bootstrap\\Html_Tab'				=> __DIR__.'/classes/html/tab.php',
	'Bootstrap\\Html_Table'			=> __DIR__.'/classes/html/table.php',
	'Bootstrap\\Html_Modal'			=> __DIR__.'/classes/html/modal.php',
	'Bootstrap\\Html_Carousel'	=> __DIR__.'/classes/html/carousel.php',
	'Bootstrap\\Pagination'			=> __DIR__.'/classes/pagination.php',
));


/* End of file bootstrap.php */