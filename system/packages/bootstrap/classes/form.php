<?php

namespace Bootstrap;

class Form extends \Fuel\Core\Form {
	
	public static function __callStatic($m, $args)
	{
		if (method_exists(static::$instance, $m))
			return call_user_func_array(array(static::$instance, $m), $args);
	}
			
}