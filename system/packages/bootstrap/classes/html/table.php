<?php

namespace Bootstrap;

class Html_Table {
	
	protected static
		$helper = null;

	protected
		$content		= array('head' => null, 'body' => null, 'footer' => null), // data to render
		$callbacks	= array(), // closures store
		$properties	= array(), // colums
		$items 			= array(), // rows
		$user_items	= array(), // Extra colums
		$attrs			= array(); // table sattributes
	
	
	/**
	 * @access public
	 * @static
	 * @param array $items (default: array())
	 * @param array $properties (default: array())
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public static function forge(array $items = array(), array $properties = array(), array $attrs = array())
	{
		is_null(self::$helper) and self::$helper = Bootstrap::forge('html');
		
		return new self($items, $properties, $attrs);
	}
	
	
	/**
	 * @access public
	 * @param array $items (default: array())
	 * @param array $properties (default: array())
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function __construct(array $items = array(), array $properties = array(), array $attrs = array())
	{
		// set properties if provided or use model properties
		$this->properties = $properties ?: $this->get_model_properties(reset($items));
		$this->attrs			= $attrs;
		
		// store models
		foreach ($items as $item) $this->add_model($item);
	}
	
	
	/**
	 * Add model in the set of items.
	 * Only supports instances of \Orm\Mode and \Model_Crud, because
	 * properties has be to defined into models
	 * 
	 * @access public
	 * @param mixed $model
	 * @return void
	 */
	public function add_model($model)
	{
		// check for Model_Crud or Model
		if (! $model instanceof \Model_Crud and ! $model instanceof \Orm\Model)
		{
			throw new \InvalidArgumentException( __METHOD__.'() '.get_class($model).' is not an instance of Model_Crud or Orm\Model');
		}
		
		return $this->items[] = $model;
	}
	
	/**
	 * Generic properties shared with both instances, under $_html_table key.
	 * $_html_table has to be public.
	 * 
	 * @access protected
	 * @param mixed $model
	 * @return void
	 */
	protected function get_model_properties($model)
	{
		// get custom properties
		if (property_exists($model, '_html_table'))
		{
			$props = $model::$_html_table;
		}
		// try to get model properties: Orm has method properties()
		elseif (property_exists($model, '_properties'))
		{
			$props = (method_exists($model, 'properties')) ? $model->properties() : $model::$_properties;
			$props = array_combine(array_values($props), $props);
		}
		else
		{
			throw new \InvalidArgumentException( __FUNCTION__.'('.get_class($model).'): You must set _html_table properties into your model');
		}
		return $props;
	}	
	
	/**
	 * @access public
	 * @param mixed $key
	 * @param mixed $callback
	 * @return void
	 */
	public function cell($key, $callback)
	{
		$this->callbacks[$key] = $callback;
		
		return $this;
	}
	
	
	/**
	 * Add an user row.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public function append($title, $callback, $ukey = null)
	{
		$this->prepare_insert($callback, $ukey);
		
		$this->properties[$ukey] = $title;
		
		return $this;
	}
	
	
	/**
	 * Prepend an user row.
	 * 
	 * @access public
	 * @param mixed $title
	 * @param mixed $callback
	 * @return void
	 */
	public function prepend($title, $callback, $ukey = null)
	{
		$this->prepare_insert($callback, $ukey);
		
		\Arr::prepend($this->properties, $ukey, $title);
		
		return $this;
	}
	
	/**
	 * Insert after a specific key.
	 * 
	 * @access public
	 * @return void
	 */
	public function insert_after($key, $title, $callback, $ukey = null)
	{
		$this->prepare_insert($callback, $ukey);
		
		\Arr::insert_after_key($this->properties, $title, $key);
		
		$this->properties = \Arr::replace_key($this->properties, array(0 => $ukey));
		
		return $this;
	}
	
	/**
	 * Insert before a specific key.
	 * 
	 * @access public
	 * @return void
	 */
	public function insert_before($key, $title, $callback, $ukey = null)
	{
		$this->prepare_insert($callback, $ukey);

		\Arr::insert_before_key($this->properties, $title, $key);
		
		$this->properties = \Arr::replace_key($this->properties, array(0 => $ukey));
		
		return $this;
	}
	
	/**
	 * Shared routine beetween append, prepend, insert_*.
	 * 
	 * @access protected
	 * @param mixed $callback
	 * @param mixed $user_key
	 * @return void
	 */
	protected function prepare_insert($callback, &$ukey)
	{
		if (! is_callable($callback))
		{
			throw new \InvalidArgumentException(__METHOD__." You should provide a valid closure object");
		}
		
		! $ukey and $ukey = \Str::random('unique');
		
		$this->callbacks[$ukey] = $callback;
		
		return $ukey;
	}
	
	/**
	 * Render the html table element.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		foreach ($this->content as $key => $content)
		{
			if (! $this->content[$key]) $this->$key();
		}

		$output  = $this->content['head'];
		$output .= $this->content['body'];
		$output .= $this->content['footer'];
		
		self::$helper->add_template($this->attrs);
		
		$class = array('table');
		
		foreach ($this->attrs as $key => $val)
		{
			switch ($key)
			{
				case 'bordered':
				case 'striped':
				case 'condensed':
				$val === true and $class[] = 'table-'.$key;
				break;
			}
		}

		self::$helper->merge_classes($this->attrs, $class)->clean_attrs('table', $this->attrs);
		
		return html_tag('table', $this->attrs, $output);
	}
	
	/**
	 * Set the rows for head
	 * 
	 * @access public
	 * @param mixed $properties
	 * @return void
	 */
	protected function head($properties = array())
	{
		$properties = $properties ?: $this->properties;
		
		// language namespace (from attrs)
		$lang 	= isset($this->attrs['language']) ? $this->attrs['language'] : '';
		$fields = '<tr>';
		
		foreach ($properties as $prop)
		{
			// check if translation exists or fallback to the defined value in model / properties
			$fields .= html_tag('th', array(), __($lang.'.'.$prop) ?: $prop);
		}
		$fields .= '</tr>';
		
		$this->content['head'] = html_tag('thead', array(), $fields);
		
		return $this;
	}
	
	
	/**
	 * Generates body.
	 * 
	 * @access public
	 * @return void
	 */
	protected function body($items = array())
	{
		$items		= $items ?: $this->items;
		$contents = '';
		
		// use alternator if defined
		$alt = (array_key_exists('alternator', $this->attrs))
			? call_user_func_array(array('Str', 'alternator'), explode('|', $this->attrs['alternator']))
			: function(){ return null; };
		
		// set rows..
		foreach ($items as $id => $item)
		{
			$contents .= '<tr class="'.$alt().'">';
			foreach ($this->properties as $key => $v)
			{
				$content 	 = isset($this->callbacks[$key]) ? $this->callbacks[$key]($item) : $this->get_model_property($item, $key);
				$contents .= html_tag('td', array(), $content);
			}
			$contents .= '</tr>';
		}
		$this->content['body'] = html_tag('tbody', array(), $contents);
		
		return $this;
	}
	
	
	/**
	 * Set elements to the footer.
	 * 
	 * @access public
	 * @return void
	 */
	public function footer($items = array())
	{
		is_string($items) and $items = array($items);
		$attrs		= array();
		$n_proprs	= count($this->properties);
		$n_diff		= $items ? intval($n_proprs / count($items)) : 0;
		$i				= 0;

		$footer	= '<tr>';
		$last = end($items);
		foreach ($items as $item)
		{
			// set colspan to match table columns
			if ($item === $last and ($i !== $n_proprs))
			{
				$n_diff = $n_proprs - $i;
			}
			$footer .= html_tag('td', array('colspan' => $n_diff), $item);
			$i += $n_diff;
		}
		$footer .= '</td>';
		
		$this->content['footer'] = html_tag('tfoot', array(), $footer);
		
		return $this;
	}
	
	/**
	 * Find a model property, and get relation if needed.
	 * 
	 * @access protected
	 * @param mixed $model
	 * @param mixed $prop
	 * @return void
	 */
	protected function get_model_property($model, $path)
	{
		$prop = $model;
		foreach (explode('.', $path) as $obj)
		{
			if (isset($prop->$obj))
			{
				$prop = (is_array($prop->$obj)) ? (string)count($prop->$obj) : $prop->$obj;
			}
		}
		return (is_string($prop)) ? $prop : null;
	}
	
}