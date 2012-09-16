<?php


/**
 * Use . instead of / for paths, only an esthetic consideration
 * 
 * @extends Fuel\Core\View
 */
class View extends Fuel\Core\View {
	
	/**
	 * replace '.' with '/' before to let Fuel\Core\View do the job.
	 * 
	 * @access public
	 * @static
	 * @param mixed $file (default: null)
	 * @param mixed $data (default: null)
	 * @param mixed $auto_filter (default: null)
	 * @return void
	 */
	public static function forge($file = null, $data = null, $auto_filter = null)
	{
		return new static(str_replace('.', '/', $file), $data, $auto_filter);
	}
	
}