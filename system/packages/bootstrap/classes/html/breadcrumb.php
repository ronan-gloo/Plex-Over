<?php

namespace Bootstrap;


/**
 * Build Bootstrap breacrumb component.
 */
class Html_Breadcrumb {
	
	/**
	 * Bootstrap helper
	 * 
	 * (default value: null)
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $helper;
	
	/**
	 * Breadcrumb html attributes
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $attrs;
	
	
	/**
	 * Breadcrumb items
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $items;
	
	/**
	 * Instanciate class and load helper on first run.
	 * 
	 * @access public
	 * @static
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public static function forge(array $attrs = array(), array $items = array())
	{
		is_null(self::$helper) and self::$helper = Bootstrap::forge('html');
				
		return new self($attrs, $items);
	}
	
	/**
	 * Store breadcrumb attributes and add items if provided.
	 * 
	 * @access public
	 * @param mixed $attrs
	 * @param mixed $items
	 * @return void
	 */
	public function __construct($attrs, $items)
	{
		$this->attrs = $attrs;
		
		if ($items) foreach ($items as $item) $this->items = $items;
	}
	
	/**
	 * Auto render breadcurmb by calling render() method.
	 * 
	 * @access public
	 * @return void
	 */
	public function __toString()
	{
		return $this->render();
	}
	
	/**
	 * Add item to the breadcrumb.
	 * 
	 * @access public
	 * @param mixed $anchor
	 * @param mixed $title
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function add($anchor, $title, array $attrs = array())
	{
		$this->items[] = array($anchor, $title, $attrs);
		
		return $this;
	}
	
	
	/**
	 * Render Breadcrumb Html.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		$class 	 = array('breadcrumb');
		$content = array();
		$actived = array('class' => 'active');
		$lastitem= end($this->items);
		
		// default string attr is a custom items separator
		is_string($this->attrs) and $this->attrs = array('divider' => $this->attrs);
		
		if (array_key_exists('divider', $this->attrs))
		{
			$sep = $this->attrs['divider']; unset($this->attrs['divider']);
		}
		else
		{
			$sep = '/';
		}
		$sep = html_tag('span', array('class' => 'divider'), $sep);
		
		foreach ($this->items as $key => $item)
		{
			$css	= ($item === $lastitem) ? $actived : array();
			$css and $sep = null;
			$item = is_array($item) ? static::$helper->item_array($item, false) : $item;
			$content[] = html_tag('li', $css, $item.$sep);
		}
		
		static::$helper->add_template($this->attrs)->merge_classes($this->attrs, $class);
		
		return html_tag('ul', $this->attrs, implode("\n", $content));
	}
	
}