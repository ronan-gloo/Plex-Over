<?php


/**
 * Build video elements.
 * If the metadata has more than 1 part, we display
 * each part inside a tab.
 * 
 * @extends Widget
 */
class Widget_Video extends Widget {
	
	protected static
		
		/**
		 * Store shared options for Html::video() $attrs
		 * 
		 * @var mixed
		 * @access public
		 */
		$options	= array(),
		
		
		/**
		 * Store Model_Metadata when forge() is called 
		 * 
		 * (default value: null)
		 * 
		 * @var mixed
		 * @access public
		 */
		$metadata	= null;
	
	
	/**
	 * Store the .
	 * 
	 * @access public
	 * @static
	 * @param mixed $model
	 * @return void
	 */
	public static function forge(Model_Metadata $metadata, array $options = array())
	{
		static::$options	= $options;
		static::$metadata	= $metadata;
		
		return new self();
	}
	
	
	/**
	 * Set an option.
	 * @access public
	 * @param mixed $key: option name
	 * @param mixed $val: option val
	 * @return void
	 */
	public function set($key, $val)
	{
		static::$options[$key] = $val;
		
		return $this;
	}
	
	/**
	 * Build the html and returns it 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		$videos = array();
		
		foreach (static::$metadata->media->parts as $part) $videos[] = Html::video($part, static::$options);
		
		// Set a carousel if more than 1 element
		if (count($videos) > 1)
		{
			$tabs = Html::tabs();
			
			foreach ($videos as $i => $vid)
			{
				$tabs->add(__('app.part').' '.($i+1))->set($vid);
			}
			
			$output = $tabs->render();
		}
		// Just on item
		else
		{
			$output = reset($videos);
		}
		
		return $output;
	}
	
}