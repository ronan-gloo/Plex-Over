<?php

namespace Bootstrap;

class Html_Modal {
	
	protected static $helper;
	
	// instance storage
	protected
		$attrs			= array(),
		$title			= null,
		$title_tag 	= 'h3',
		$body				= array(),
		$actions		= array();
		
		
	public static function forge(array $attrs = array())
	{
		is_null(self::$helper) and self::$helper = Bootstrap::forge('html');
		
		return new self($attrs);
	}
	
	/**
	 * Construct the 
	 * 
	 * @access public
	 * @static
	 * @param mixed $title
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function __construct(array $attrs = array())
	{
		$this->attrs = $attrs;
	}
	
	
	/**
	 * Set the modal title.
	 * Html tag can be defined under $tag arg
	 * 
	 * @access public
	 * @param mixed $title (default: null)
	 * @param mixed $tag (default: null)
	 * @return void
	 */
	public function title($title = null, $tag = null)
	{
		$this->_title = $title;
		
		$tag and $this->title_tag = $tag;
		
		return $this;
	}
	
	
	/**
	 * Set the modal body.
	 * 
	 * @access public
	 * @param mixed $html (default: null)
	 * @return void
	 */
	public function body($html = null)
	{
		$this->body[] = $html;
		
		return $this;
	}
	
	
	/**
	 * Need to wrap Modal content / actions into a form ?
	 * alias to Form::open()
	 * 
	 * @access public
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function form(array $attrs = array())
	{
		$this->form_attrs = $attrs;
		
		return $this;
	}
	
	/**
	 * Add an action (modal footer).
	 * 
	 * @access public
	 * @param mixed $html
	 * @return void
	 */
	public function action($html)
	{
		$this->actions[] = $html;
		
		return $this;
	}
	
	/**
	 * Build the modal content.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function build()
	{
		$header	 = html_tag('a', array('class' => 'close', 'data-dismiss' => 'modal'), '&times;');
		$header .= html_tag($this->title_tag, array(), $this->_title);
		$header	 = html_tag('div', array('class' => 'modal-header'), $header);
		
		$content 	= html_tag('div', array('class' => 'modal-body'), implode($this->body));
		$content .= html_tag('div', array('class' => 'modal-footer'), implode($this->actions));
		
		// form ?
		if (property_exists($this, '_form_attrs'))
		{
			$header 	.= Form::open($this->form_attrs);
			$content 	.= Form::close();
		}
		return $header.$content;
	}
	/**
	 * Build the Html then returns it.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		self::$helper->add_template($this->attrs);
		
		$class = array('modal');
		
		// Hidden by default
		if (! array_key_exists('hide', $this->attrs))
		{
			$this->attrs['hide'] = true;
		}
		// Set attributes
		foreach ($this->attrs as $name => $attr)
		{
			switch ($name)
			{
				case 'hide':
				case 'fade':
				$attr === true and $class[] = $name;
				break;
			}
		}
		self::$helper->merge_classes($this->attrs, $class)->clean_attrs('modal', $this->attrs);
		
		// Output the modal
		return html_tag('div', $this->attrs, $this->build());
	}
	
}