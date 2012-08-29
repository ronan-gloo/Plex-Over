<?php

/**
 * Application specific methods for Strings.
 * 
 * @extends Fuel\Core\Str 
 */
class Str extends Fuel\Core\Str {
	
	
	/**
	 * Shift a string "a la" Array.
	 * 
	 * @access public
	 * @static
	 * @param mixed $str
	 * @param mixed $pattern
	 * @return void
	 */
	public static function shift($pattern, $str)
	{
		$tmp = explode($pattern,$str);
		
		return array_shift($tmp);
	}
	
	
	/**
	 * Pop a string "a la" Array.
	 * 
	 * @access public
	 * @static
	 * @param mixed $str
	 * @param mixed $pattern
	 * @return void
	 */
	public static function pop($pattern, $str)
	{
		$tmp = explode($pattern,	$str);
		
		return array_pop($tmp);
	}
	
}