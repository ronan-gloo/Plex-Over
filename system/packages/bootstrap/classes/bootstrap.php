<?php

namespace Bootstrap;

class Bootstrap {
	
	protected static
		$_use_strict	= false;
	
	protected
		$called_by	= null,
		$conf				= null;
	
	
	public static function forge($class_name)
	{
		return new self($class_name);
	}
	
	/**
	 * Static constructor
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public function __construct($class_name)
	{
		$this->called_by = $class_name;
	}
	
	
	/**
	 * If set to true, the class will validate each arg, and throw an exception if incorrect.
	 * 
	 * @access public
	 * @static
	 * @param mixed $bool
	 * @return void
	 */
	public static function use_strict($bool)
	{
		if (is_bool($bool))
		{
			self::$_use_strict = $bool;
		}
	}
	
	/**
	 * Chagement des fichiers de configuration
	 * 
	 * @access protected
	 * @return void
	 */
	public function get_conf($prefix = 'default_')
	{
		$path		= 'bootstrap/'.$prefix.$this->called_by;
		$alias	= 'bootstrap_'.$prefix.$this->called_by;
		
		if (! $conf = \Config::get($alias))
		{
			$conf = \Config::load($path, $alias);
		}

		return $conf;
	}
	
	/**
	 * Check an arg into array or against value.
	 * 
	 * 
	 * @access protected
	 * @static
	 * @param mixed string $needle
	 * @param mixed $haystack
	 * @return Bool
	 */
	public function validate_arg($needle, $haystack)
	{
		// use en wild card
		if (in_array('*', (array)$haystack))
		{
			return true;
		}
		switch (is_array($haystack))
		{
			case 1:
			$out = (in_array($needle, $haystack));
			break;
			
			case 0:
			$out = ($needle === $haystack);
			break;
		}
		if ($out === false)
		{
			throw new \InvalidArgumentException("'$needle' is not a valid argument for ". get_called_class());
		}
		return $out;
	}
	
	/**
	 * Remove extra attributes before render.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public function clean_attrs($ckey, array &$attrs)
	{
		// Key not defined, throw an exception to prevent exotics html attrs
		$conf = $this->get_conf();
		
		if (empty($conf[$ckey]))
		{
			throw new \OutOfBoundsException(__CLASS__.'::'.__FUNCTION__.'(): "'.$ckey.'" config key not found or invalid');
		}
		foreach ($conf[$ckey]['attrs'] as $name => $vals)
		{
			if (array_key_exists($name, $attrs))
			{
				if ($vals and static::$_use_strict === true)
				{
					$this->validate_arg($attrs[$name], $vals);
				}
				unset($attrs[$name]);
			}
		}
		return $this;
	}
	
	
	/**
	 * Mainly to merge exiting class with specific Bootstrap classes
	 * 
	 * @access public
	 * @static
	 * @param mixed $a_attr
	 * @param mixed $b_attrs
	 * @return void
	 */
	public function merge_classes(array &$attrs, array $classes)
	{
		if (! array_key_exists('class', $attrs))
		{
			$attrs['class'] = '';
		}
		else
		{
			$attrs['class'] .= ' ';
		}
		
		$attrs['class'] .= implode(' ', $classes);
		
		return $this;
	}
	
	
	/**
	 * Execute: add_template, merge_classes, clean_attrs.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param array &$attrs
	 * @param array $classes
	 * @return void
	 */
	public function merge_attrs($key, array &$attrs, array $classes)
	{
		return $this->add_template($attrs)->merge_classes($attrs, $classes)->clean_attrs($key, $attrs);
	}
	
	/**
	 * Shared function (html::button / form::button) to set atributes.
	 * 
	 * @access public
	 * @static
	 * @param mixed &$text
	 * @param mixed &$attrs
	 * @return void
	 */
	public function set_button_attrs(&$value, &$attrs)
	{
		$this->add_template($attrs);
		
		// juste a string: it should be the color
		if ($attrs and is_string($attrs))
		{
			$attrs = array('status' => $attrs);
		}
		$class = array('btn');
		
		foreach ($attrs as $key => $attr)
		{
			switch ($key)
			{
				case 'status':
				case 'size':
				$class[] = 'btn-'.$attr;
				break;

				case 'state':
				$class[] = $attr;
				($attr === 'disabled') and $attr['disabled'] = 'disabled';
				break;
			}
		}
		$this->set_icon($value, $attrs);
		$this->merge_classes($attrs, $class);
		
		return $this;
	}
	
	/**
	 * Merge attributes from a template (located in config file)
	 * 
	 * @access protected
	 * @static
	 * @param mixed $tpl
	 * @param mixed $attrs
	 * @return void
	 */
	public function add_template(&$attrs)
	{
		if (isset($attrs['from']))
		{
			$this->get_conf(null);
			
			$tpl_name			= $attrs['from'];
			$config_path	= 'bootstrap_'.$this->called_by.'.templates.'.$tpl_name;
			
			if ($tpl = \Config::get($config_path))
			{
				$attrs = $attrs + $tpl;
			}
			elseif (! $tpl and self::$_use_strict === true)
			{
				throw new \OutOfBoundsException(__METHOD__.'(): "'.$tpl_name.'" Template does not exists');
			}
			unset($attrs['from']);
		}
		
		return $this;
	}
	
	
	/**
	 * Add an icon defined in attrs.
	 * This method auto set icon to white if color is different of default
	 * 
	 * @access public
	 * @static
	 * @param mixed $attrs
	 * @return void
	 */
	public function set_icon(&$text, &$attrs)
	{
		if (isset($attrs['icon']))
		{
			$icss = array('icon', 'icon-'.$attrs['icon']);
			
			if (array_key_exists('status', $attrs) and $attrs['status'] !== 'default')
			{
				$icss[] = 'icon-white';
			}
			
			$class = array();
			
			$this->merge_classes($class, $icss);
			
			$icon = html_tag('i', $class, '');
			
			if (! array_key_exists('icon-pos', $attrs))
			{
				$attrs['icon-pos'] = 'left';
			}
			
			switch($attrs['icon-pos'])
			{
				case 'left':
				$text = $icon.' '.$text;
				break;
				
				case 'right':
				$text = $text.' '.$icon;
				break;
			}
		}
		return $this;
	}
	
	/**
	 * Returns active class.
	 * 
	 * @access protected
	 * @param mixed $active
	 * @return void
	 */
	public function active_to_attr($active)
	{
		return ($active === true) ? array('class' => 'active') : array();
	}

}
