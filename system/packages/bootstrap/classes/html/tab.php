<?php

namespace Bootstrap;

class Html_Tab {
	
	protected static
		$helper			= null, // Bootstrap helper
		$inst_num 	= 0, // current instance id
		$inst_id		= 'tabs'; // default id if not provided in attrs
	
	protected
		$attrs				= array(), // main attributes
		$index 				= 0, // index to increment for each tab after set()
		$active_index = 0, // set the index to activate
		$id						= null, // id of the instance
		$tabs					= array(), // store for tabs
		$contents			= array(); // store for tabs content
		
	
	/**
	 * @access public
	 * @static
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public static function forge(array $attrs = array())
	{
		is_null(self::$helper) and self::$helper = Bootstrap::forge('html');
		
		// increment instance num, if we need to render more tan than 1 tabs element
		self::$inst_num++; 

		return new self($attrs);
	}
	
	/**
	 * @access public
	 * @static
	 * @param mixed $title
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function __construct(array $attrs = array())
	{
		// store main attributes for render()
		$this->attrs = $attrs;
		
		// check for the instance id if spefified
		$this->id = (array_key_exists('id', $attrs)) ? $attrs['id'].'-tab' : self::$inst_id.self::$inst_num.'-tab';
	}
	
	
	/**
	 * Set a tab anchor.
	 * 
	 * @access public
	 * @param mixed $str
	 * @param array $attrs (default: array())
	 * @param bool $active (default: false)
	 * @return void
	 */
	public function add($str, array $attrs = array())
	{
		$this->tabs[$this->index] = array(
			'body' 	=> $str,
			'attrs'	=> $attrs
		);		
		return $this;
	}
	
	
	/**
	 * Set a content tab-pane.
	 * 
	 * @access public
	 * @param mixed $str
	 * @param array $attrs (default: array())
	 * @param bool $active (default: false)
	 * @return void
	 */
	public function set($str, array $attrs = array())
	{
		$this->contents[$this->index] = array(
			'body' 	=> $str,
			'attrs'	=> $attrs
		);
		// further add() calls will be on the next pointer
		$this->index++;
		
		return $this;
	}
	
	
	/**
	 * Set the 'actice' index on launch
	 * 
	 * @access public
	 * @param mixed $index
	 * @return void
	 */
	public function active($index)
	{
		$this->active_index = (int)$index; // because we assume 
		
		return $this;
	}
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function _build_tabs()
	{
		$tabs = '';
		foreach ($this->tabs as $index => $tab)
		{
			$anchor = html_tag('a', $this->set_iref('href', $tab['attrs'], $index), $tab['body']);
			$active = self::$helper->active_to_attr($index === $this->active_index);
			$tabs	 .= html_tag('li', $active, $anchor);	
		}
		return $tabs;
	}
	
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function _build_contents()
	{
		$contents = '';
		
		foreach ($this->contents as $index => $content)
		{
			$class = self::$helper->active_to_attr($index === $this->active_index);
			$class = array('tab-pane') + $class;
			
			self::$helper->merge_classes($content['attrs'], $class);
			
			$contents .= html_tag('div', $this->set_iref('id', $content['attrs'], $index), $content['body']);
		}
		return $contents;
	}
	
	/**
	 * Render the html.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		$mclass = array('nav', 'nav-tabs');
		$tclass = array('tabbable');
		
		self::$helper->add_template($this->attrs);
		
		foreach ($this->attrs as $name => $attr)
		{
			switch ($name)
			{
				case 'type':
				$mclass[1] = 'nav-'.$attr;
				break;
				
				case 'stacked':
				$attr === true and $mclass[] = 'nav-'.$name;
				break;
				
				case 'direction':
				$tclass[] = 'tabs-'.$attr;
				break;
				
				case 'active':
				$this->active($attr);
				break;
			}
		}

		self::$helper->merge_classes($this->attrs, $mclass)->clean_attrs('tab', $this->attrs);
		
		// Build the html
		$tabs  = html_tag('ul', $this->attrs, $this->_build_tabs());
		$tabs .= html_tag('div',array('class' => 'tab-content'), $this->_build_contents());
		
		return html_tag('div', array('class' => implode(' ', $tclass)), $tabs);
	}
	
		
	/**
	 * Set the anchor or the id following the current index.
	 * 
	 * @access protected
	 * @param mixed $attr
	 * @param mixed $attrs
	 * @return void
	 */
	protected function set_iref($attr, $attrs, $index)
	{
		if (! array_key_exists($attr, $attrs))
		{
			$index = $this->id.$index;
			
			if ($attr === 'href')
			{
				$attrs[$attr] = '#'.$index;
				$attrs['data-toggle'] = 'tab';
			}
			else
			{
				$attrs[$attr] = $index;
			}
		}
		return $attrs;
	}
		
}