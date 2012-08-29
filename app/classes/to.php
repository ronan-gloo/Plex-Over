<?php


/**
 * Generates url for Application ressources.
 */
class To {
	
	/**
	 * segments after _ are translated as $action.
	 * 
	 * @access public
	 * @static
	 * @param mixed $m
	 * @param mixed $args
	 * @return void
	 */
	public static function __callStatic($m, $args)
	{
		$args[1] = (isset($args[1])) ? $m.'.'.$args[1] : $m;
		return call_user_func_array(array('self', 'section'), $args);
	}
	
	/**
	 * Generic link builder for models: they show have a section
	 * 
	 * @access public
	 * @static
	 * @param Model $model
	 * @param string $action (default: 'index')
	 * @return void
	 */
	public static function section(Model $model, $action = 'section')
	{
		$mod = ($model instanceof Model_Section) ? $model : $model->section;
		return $mod->type().'s/'.str_replace('.', '/', $action).'/'.$model->id;
	}
	
	
	/**
	 * Link to a stream: bridge beetween the app and the server.
	 * 
	 * @access public
	 * @static
	 * @param mixed $item
	 * @param mixed $action
	 * @return void
	 */
	public static function stream($item, $action)
	{
		return Uri::base().'stream/'.$action.'/'.$item->id;
	}
	
	public static function subtitle($str)
	{
		return Uri::base().'stream/subtitle/'.$str;
	}
	
	public static function download(Model_Media_Part $part)
	{
		return Uri::base().'download/index/'.$part->id;
	}
	
	public static function thumb($item, $attrs = array())
	{
		return static::thumb_art($item, $attrs, 'thumb');
	}
	
	public static function art($item, $attrs = array())
	{
		return static::thumb_art($item, $attrs, 'art');
	}
	
	
	/**
	 * Build parameters for plater request to plex media server.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $item
	 * @param mixed $attrs
	 * @param mixed $type
	 * @return void
	 */
	protected static function thumb_art($item, $attrs, $type)
	{
		! is_array($attrs) and $attrs = array('width' => $attrs);
		
		$base = static::stream($item, 'image').'/'.$type;
		
		$prevdim = 300;
		
		foreach (array('width', 'height') as $attr)
		{
			! isset($attrs[$attr]) and $attrs[$attr] = $prevdim;
			
			$prevdim = $attrs[$attr];
		}
		
		$attrs['url'] = $item->server_path();
		$attrs['t'] 	= strtotime($item->updated_at);
		
		return $base.'?'.http_build_query($attrs).'.jpeg';
	}
	
}