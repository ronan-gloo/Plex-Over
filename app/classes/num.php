<?php


/**
 * Application specific umbers management
 * 
 * @extends Fuel\Core\Num
 */
class Num extends Fuel\Core\Num {
	
	
	/**
	 * Convert $num to percent 
	 * 
	 * @access public
	 * @static
	 * @param mixed $num: value to convert
	 * @param mixed $total: total number as ratio
	 * @param int $precision (default: 2): precision to returns
	 * @return void
	 */
	public static function to_percent($num, $total, $precision = 2)
	{
		return round(($num / (float)$total) * 100, $precision);
	}
	
} 

