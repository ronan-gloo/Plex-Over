<?php


/**
 * Format raw time data to something readable.
 */
class Time {
	
	/**
	 * Convert Milisecond to human readable format, like
	 * mm::ss or mm/min
	 * 
	 * @access public
	 * @static
	 * @param mixed $sec
	 * @param bool $padHours (default: false)
	 * @return void
	 */
	public static function duration($milisec, $seconds = false) 
  {
  	$mstr = ($seconds) ? ':' : 'min ';
  	$sec	= intval((int)$milisec / 1000);
    $hms	= "";
    
    if ($hours = intval($sec / 3600))
    {
	    $hms .= $hours. "h ";
	  } 
    
    if ($minutes = intval(($sec / 60) % 60) or $seconds === true)
    {
    	$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT).$mstr;
    }
    
    if ((! $hours and $seconds))
    { 
    	$hms .= str_pad($seconds = intval($sec % 60), 2, "0", STR_PAD_LEFT);
    }

    return $hms;
  }
}