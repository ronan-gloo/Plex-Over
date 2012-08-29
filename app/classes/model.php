<?php


/**
 * Base class for All Orm models.
 * 
 * 
 * @abstract
 * @extends Orm
 */
abstract class Model extends Orm\Model {
	
	protected static
		$_has_one			= array(),
		$_has_many		= array(),
		$_belongs_to	= array(),
		
		/**
		 * Relative path form Plex Media Server.
		 * Allow to generate urls automatically
		 * 
		 * @var mixed
		 * @access private
		 */
		$_server_path = null, 
		$_observers		= array();
	
	private static
		
		/**
		 * Allow only specific events
		 * 
		 * (default value: array('after_load', 'after_clone'))
		 * 
		 * @var string
		 * @access private
		 */
		$_valid_events = array('after_load', 'after_clone');
	
	/**
	 * Autoloader call: set observer self if methods found in @$_valid_events
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function _init()
	{
		self::set_observer_self();
	}
	
	/**
	 * autoset ObserverSelf if methods are defined
	 * 
	 * @access private
	 * @static
	 * @return void
	 */
	private static function set_observer_self()
	{
		$class 	= get_called_class();
		$events	= array();
		 
		foreach (self::$_valid_events as $event)
		{
			if (method_exists($class, '_event_'.$event))
			{
				$events[] = $event;
			}
		}
		$events and static::$_observers['Orm\\Observer_Self'] = array('events' => $events);
				
		return ($events);
	}
	
	
	/**
	 * Retuns the relative serveur path. Usefull in some contexts, like thumbs, img etc..
	 * 
	 * @access public
	 * @return void
	 */
	public function server_path()
	{
		return static::$_server_path.$this->id;
	}
	
	
	/**
	 * Get the url.
	 * 
	 * @access public
	 * @return void
	 */
	public function server_url()
	{
		return Plex::base_url().$this->server_path();
	}

}