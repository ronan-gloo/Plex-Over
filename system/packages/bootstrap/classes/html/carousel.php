<?php

namespace Bootstrap;

class Html_Carousel {
	
	protected static
		$helper		= null,
		$inst_id	= 'carousel',
		$inst_num = 0;
	
	protected
		$index	= 0,
		$active	= 0,
		$slices = array(),
		$attrs 	= array();
	
	
	/**
	 * forge function.
	 * 
	 * @access public
	 * @static
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public static function forge(array $attrs = array())
	{
		is_null(self::$helper) and self::$helper = Bootstrap::forge('html');
		
		self::$inst_num++;

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
		// store attributes
		$this->attrs = $attrs;
		
		// check for the instance id if spefified
		$this->attrs['id'] = (array_key_exists('id', $attrs)) ? $attrs['id'] : self::$inst_id.self::$inst_num;

		// check for the active item
		$this->active = (isset($attrs['active'])) ? (int)$attrs['active'] : 0;
	}
	
	
	/**
	 * Add a carousel content.
	 * 
	 * @access public
	 * @param mixed $title
	 * @param mixed $content (default: null)
	 * @return void
	 */
	public function add($img, $title = null, $content = null)
	{
		$this->slices[$this->index]['content'] = $img;
		
		$this->caption($title, $content);
		
		return $this;
	}
	
	
	/**
	 * set caption ofr the current carousel content.
	 * 
	 * @access public
	 * @param mixed $content
	 * @return void
	 */
	public function caption($title = null, $content = null, array $attrs = array())
	{
		if ($title)
		{
			$title = html_tag('h4', array(), $title);
		}
		if ($content)
		{
			$content = html_tag('p', array(), $content);
		}
		if ($title or $content)
		{
			$this->slices[$this->index]['caption'] = html_tag('div', array('class' => 'carousel-caption'), $title.$content);
		}
		$this->index++;
		
		return $this;
	}
	
	/**
	 * Set the active index on launch. Default is the first.
	 * 
	 * @access public
	 * @param mixed $index
	 * @return void
	 */
	public function active($index)
	{
		$this->active = (int)$index;
		
		return $this;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function render()
	{
		self::$helper->add_template($this->attrs);
		
		// base css class
		$class = array('carousel');
		
		// check if sliding or not
		if (! empty($this->attrs['slide']))
		{
			$class[] = 'slide';
		}
		self::$helper->merge_classes($this->attrs, $class);
		
		$inner = '';
		
		foreach ($this->slices as $index => $slice)
		{
			$attrs = self::$helper->active_to_attr($index === $this->active);
			
			self::$helper->merge_classes($attrs, array('item'));
			
			$inner .= html_tag('div', $attrs, implode($slice)); 
		}
		
		$output 	= html_tag('div', array('class' => 'carousel-inner'), $inner);
		$output  .= $this->build_nav();
		$js 			= $this->build_js();
		
		self::$helper->clean_attrs('carousel', $this->attrs);
		
		return html_tag('div', $this->attrs, $output).$js;
	}
	
	
	/**
	 * Return nav links. (next / prev)
	 * 
	 * @access protected
	 * @return void
	 */
	protected function build_nav()
	{
		$out = html_tag('a', array(
			'data-slide' 	=> 'prev',
			'class' 			=> 'left carousel-control',
			'href'				=> '#'.$this->attrs['id'],
		), '&lsaquo;');
		
		$out .= html_tag('a', array(
			'data-slide' 	=> 'next',
			'class' 			=> 'right carousel-control',
			'href'				=> '#'.$this->attrs['id'],
		), '&rsaquo;');
		
		return $out;
	}
	
	
	/**
	 * Build jquery parameters if needed.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function build_js()
	{
		$output = '';
		
		foreach ($this->attrs as $key => $attr)
		{
			switch ($key)
			{
				case 'interval':
				$params[$key] = (int)$attr;
				break;
				case 'pause':
				$params[$key] = $attr;
				break;
			}
		}
		if (! empty($params))
		{
			$output = '<script type="text/javascript">';
			$output .= '$(function(){ $("#'.$this->attrs['id'].'").carousel('.json_encode($params).'); });';
			$output .= '</script>';
		}
		return $output;
	}
	
}