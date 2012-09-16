<?php

namespace Bootstrap;

/**
 * Html class: extends Core Html to provide Bootstrap markups support.
 * 
 */
class Html extends \Fuel\Core\Html {
	
	/**
	 * Bootstrap helper...
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static
		$helper,
		$conf;
	
	/**
	 * Called by the autoloader
	 * We user le class Bootstrap as an internal helper.
	 * PHP 5.4 Traits will be welcome here...
	 * for common tasks
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function _init()
	{
		is_null(static::$helper) and static::$helper = Bootstrap::forge('html');
	}
	
	/**
	 * Generate a 'label' html element.
	 * 
	 * @access public
	 * @static
	 * @param mixed $text. (default: null)
	 * @param mixed $color. (default: null)
	 * @param array $attrs. (default: array())
	 * @return void
	 */
	public static function label($text, $attrs = array())
	{
		return self::label_and_badge($text, $attrs, 'label');
	}
	
	/**
	 * Generate a badge html element
	 * 
	 * @access public
	 * @static
	 * @param mixed $text. (default: null)
	 * @param mixed $color. (default: null)
	 * @param array $attrs. (default: array())
	 * @return void
	 */
	public static function badge($text, $attrs = array())
	{
		return self::label_and_badge($text, $attrs, 'badge');
	}
		
	/**
	 * Add "button" mekthod to html class.
	 * It follow Html::anchor() arguments.
	 * 
	 * @access public
	 * @static
	 * @param mixed $href
	 * @param mixed $text. (default: null)
	 * @param array $attr. (default: array())
	 * @param mixed $secure. (default: null)
	 * @return void
	 */
	public static function button($href, $text = null, $attrs = array(), $secure = false)
	{
		static::$helper->set_button_attrs($text, $attrs)->clean_attrs('button', $attrs);
		
		return parent::anchor($href, $text, $attrs, $secure);
	}

	/**
	 * Build an Alert Block component.
	 * Extra attrs: 'type', 'status', 'close'
	 * 
	 * @access public
	 * @static
	 * @param mixed $title. (default: null): Alert title
	 * @param mixed $content. (default: null) : Content of the alert
	 * @param array $attrs. (default: array()) : attributes.
	 * If string, color argument is supposed.
	 * @return void
	 */
	public static function alert($title = null, $text = null, $attrs = array())
	{
		// by default, string indicates the status key
		is_string($attrs) and $attrs = array('status' => $attrs);
		
		!array_key_exists('type', $attrs) and $attrs['type'] = 'inline';
		
		static::$helper->add_template($attrs);
		
		$class = array('alert');
		
		foreach ($attrs as $name => $attr)
		{
			switch ($name)
			{
				case 'close':
				$attr and $content[0] = html_tag('a', array('class' => 'close', 'data-dismiss' => 'alert'), '&times;');
				$attr === 'fade' and $class[] = 'fade in';
				break;
				
				case 'type':
				$content[1] = $attr === 'block'
					? html_tag('h4', array('class' => 'alert-heading'), $title)
					: html_tag('strong', array(), $title);
				break;
				
				case 'status':
				$class[] = 'alert-'.$attr;
				break;
			}
		}
		
		$content[2] = $text;
		
		// reorder elements
		ksort($content);
		
		static::$helper->merge_classes($attrs, $class)->clean_attrs('alert', $attrs);
		
		return html_tag('div', $attrs, implode("\n", $content));
	}

	/**
	 * progress function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function progress($percent, $attrs = array())
	{
		is_string($attrs) and $attrs = array('status' => $attrs);
		
		static::$helper->add_template($attrs);
		$class = array('progress');
		foreach ($attrs as $name => $attr)
		{
			switch ($name)
			{
				case 'striped':
				$attr === true and $class[] = 'progress-'.$name;
				break;
				case 'active':
				$attr === true and $class[] = $name;
				break;
				case 'status':
				$class[] = 'progress-'.$attr;
				break;
			}
		}
		static::$helper->merge_classes($attrs, $class)->clean_attrs('progress', $attrs);
		$bar = '<div class="bar" style="width:'.$percent.'%"></div>';
		return html_tag('div', $attrs, $bar);
	}
		
	/**
	 * Build a Nav List
	 * 
	 * @access public
	 * @static
	 * @param mixed $content: string or array
	 * @param array $attrs. (default: array())
	 * @return void
	 */
	public static function navlist($contents, $attrs = array())
	{
		$out		= array();
		$class 	= array('nav', 'nav-list'); 
		
		foreach ($contents as $content)
		{
			$out[] = (is_string($content))
				// if string, create a header...
				? html_tag('li', array('class' => 'nav-header'), $content)
				// or a link
				: static::$helper->item_array($content);
		}
		
		static::$helper->add_template($attrs)->merge_classes($attrs, $class);
		
		return html_tag('ul', $attrs, implode("\n", $out));
	}

	/**
	 * @access public
	 * @static
	 * @return void
	 */
	public static function dropdown_button($text, array $items, $attrs = array(), $tag = 'div')
	{
		$content = static::_build_dropdown($text, $items, $attrs, 'button');
		
		return html_tag($tag, array('class' => 'btn-group'), $content);
	}
	
	/**
	 * @access public
	 * @static
	 * @return void
	 */
	public static function dropup_button($text, array $items, $attrs = array(), $tag = 'div')
	{		
		$content = static::_build_dropdown($text, $items, $attrs, 'button');
		
		return html_tag($tag, array('class' => 'btn-group dropup'), $content);
	}
	
	/**
	 * @access public
	 * @static
	 * @return void
	 */
	public static function dropdown($text, array $items, $attrs = array(), $tag = 'div')
	{
		$content = static::_build_dropdown($text, $items, $attrs);
		return html_tag($tag, array('class' => 'dropdown'), $content);
	}

	/**
	 * @access public
	 * @static
	 * @return void
	 */
	public static function dropup($text, array $items, $attrs = array(), $tag = 'div')
	{
		$content = static::_build_dropdown($text, $items, $attrs);
		return html_tag($tag, array('class' => 'dropdown dropup'), $content);
	}
	
	
	// --------------------------------------------------------------------
	// PLUGINS
	// --------------------------------------------------------------------
	
	/**
	 * @access public
	 * @static
	 * @return void
	 */
	public static function modal(array $attrs = array())
	{
		return Html_Modal::forge($attrs);
	}

	public static function tabs(array $attrs = array())
	{
		return Html_Tab::forge($attrs);
	}
	
	public static function carousel(array $attrs = array())
	{
		return Html_Carousel::forge($attrs);
	}	
	
	public static function table(array $items = array(), array $rpoperties = array(), array $atrs = array())
	{
		return Html_Table::forge($items, $rpoperties, $atrs);
	}	
	
	public static function breadcrumb($attrs = array(), array $items = array())
	{
		return Html_Breadcrumb::forge($attrs, $items);
	}
	
	// --------------------------------------------------------------------
	// INTERNAL METHODS
	// --------------------------------------------------------------------
	
	/**
	 * Label & badge methods share the same workflow.
	 * We just get a diffrent config entry
	 * 
	 * @access protected
	 * @static
	 * @return void
	 */
	protected static function label_and_badge($text, $attrs, $ent)
	{
		if (! $attrs)
		{
			$attrs = array('status' => 'default');
		}
		if (is_string($attrs))
		{
			$attrs = array('status' => $attrs);
		}
		
		$class = array($ent, $ent.'-'.$attrs['status']);
		
		static::$helper->merge_attrs('label_badge', $attrs, $class);
		
		return html_tag('span', $attrs, $text);
	}

	/**
	 * Build the entire dropdown.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $text
	 * @param mixed $items
	 * @param mixed $attrs
	 * @param string $tag (default: 'span')
	 * @return void
	 */
	protected static function _build_dropdown($text, $items, $attrs, $tag = 'a')
	{
		$caret= '<span class="caret"></span>';
		$css	= ' dropdown-toggle';
		$menu	= 'dropdown-menu';
		$data	= array('data-toggle' => 'dropdown');
		$list	= array();
		
		// check for a template
		static::$helper->add_template($attrs);

		! array_key_exists('class', $attrs) and $attrs['class'] = '';
				
		// set the class for the list
		$is_split	= array_key_exists('split', $attrs);
		$listcss	= array_key_exists('pull', $attrs) ? $menu.' pull-'.$attrs['pull'] : $menu;
	
		// clean dropdown attributes
		static::$helper->clean_attrs('dropdown', $attrs);

		// Set buttons attributes
		if ($tag === 'button')
		{
			static::$helper->set_button_attrs($text, $attrs)->clean_attrs('button', $attrs);
		}
		// add separator
		if ($tag === 'button' and $is_split)
		{
			$main = html_tag($tag, $attrs, $text);
			$attrs['class'] .= $css;
			$main .= html_tag($tag, $attrs + $data, $caret);
		}
		else
		{
			$attrs['class'] .= $css;
			$main  = html_tag($tag, $attrs + $data, $text." ".$caret);
		}
		
		// Build the list
		foreach ($items as $item)
		{
			switch (gettype($item))
			{
				case 'NULL':
				$list[] = html_tag('li', array('class' => 'divider'), '');
				break;

				case 'array':
				$list[] = static::$helper->item_array($item);
				break;

				case 'string':
				$list[] = html_tag('li', array(), $item);
				break;
			}
		}
		$main .= html_tag('ul', array('class' => $listcss), implode("\n", $list));
		
		return $main;
	}	
}