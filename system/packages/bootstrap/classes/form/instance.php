<?php

namespace Bootstrap;

/**
 * Hook the form class to make it workink with twitter bootstrap
 * 
 */
class Form_Instance extends \Fuel\Core\Form_Instance {
	
	protected static
		$helper, 
		$conf;
	
	protected
		$line_opened = false, // generate auto markups (ie: line_open())
		$controls_opened = false; // store output if a line is opened
	
	/**
	 * Called by the autoloader
	 * We user le class Bootstrap as an internal helper
	 * for common tasks
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function _init()
	{
		is_null(static::$helper) and static::$helper = Bootstrap::forge('form');
	}
	
	/**
	 * @access public
	 * @static
	 * @param array $attributes. (default: array())
	 * @param array array $hidden. (default: array())
	 * @return void
	 */
	public function open($attrs = array(), array $hidden = array())
	{
		static::$helper->add_template($attrs);
		
		$class = array();
		
		if (array_key_exists('type', $attrs))
		{
			$class[] = 'form-'.$attrs['type'];
		}
		
		static::$helper->merge_classes($attrs, $class)->clean_attrs('open', $attrs);
		
		return parent::open($attrs, $hidden);
	}
	
	
	/**
	 * Define a state if needed.
	 * 
	 * @access public
	 * @static
	 * @param array $attrs (default: array())
	 * @param mixed $legend (default: null)
	 * @return void
	 */
	public function fieldset_open($attrs = array(), $legend = null)
	{
		$attrs = $this->control_fieldset_open($attrs);
		
		return parent::fieldset_open($attrs, $legend);
	}
	
	
	/**
	 * Generate a control-group type div open.
	 * 
	 * @access public
	 * @static
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function control_open($attrs = array())
	{
		$attrs = $this->control_fieldset_open($attrs);
		$this->line_opened = true;
		return '<div '.array_to_attr($attrs).'>';
	}
	
	/**
	 * Close an opened line.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public function control_close()
	{
		$this->controls_opened = $this->line_opened = false;
		return "</div>\n</div>"; // close .controls div
	}
	
	
	/**
	 * Open an action wrapper.
	 * 
	 * @access public
	 * @return void
	 */
	public function action_open($attrs = array())
	{
		static::$helper->add_template($attrs);
		$class = array('form-actions');
		static::$helper->merge_classes($attrs, $class);
		
		return '<div '.array_to_attr($attrs).'>';
	}
	
	
	/**
	 * Close an action wrapper.
	 * 
	 * @access public
	 * @return void
	 */
	public function action_close()
	{
		return "</div>\n";
	}
	
	/**
	 * Catch attributes if a line is opened.
	 * 
	 * @access public
	 * @static
	 * @param mixed $label
	 * @param mixed $id (default: null)
	 * @param array $attributes (default: array())
	 * @return void
	 */
	public function label($label, $id = null, array $attrs = array())
	{
		if ($this->line_opened === true)
		{
			static::$helper->add_template($attrs);
			static::$helper->merge_classes($attrs, array('control-label'));
		}
		
		return parent::label($label, $id, $attrs);
	}
	
	
	/**
	 * Generate an help element.
	 * 
	 * @access public
	 * @static
	 * @param mixed $text: string texte
	 * @param array $attrs (default: array()): string or array
	 * @return void
	 */
	public function help($text, $attrs = array())
	{
		// convert to array
		($attrs and is_string($attrs)) and $attrs = array('type' => $attrs);
		
		static::$helper->add_template($attrs);
		
		if(! array_key_exists('type', $attrs))
		{
			$attrs['type'] = 'inline';
		}
		// set the class
		$class[] = 'help-'.$attrs['type'];
		
		// define appropriate tab
		$tag = $attrs['type'] === 'inline' ? 'span' : 'p';
		
		static::$helper->merge_classes($attrs, $class)->clean_attrs('help', $attrs);
		
		return html_tag($tag, $attrs, $text);
	}
		
	/**
	 * @param   string
	 * @param   array
	 * @return  string
	 */
	public function input($field, $value = null, array $attrs = array())
	{
		// Check if 'button'
		if (isset($attrs['type']) and $attrs['type'] === 'button')
		{
			static::$helper->set_button_attrs($attrs);
			$output = parent::button($field, $value, $attrs);
		}
		else
		{
			$output = $this->_input($field, $value, $attrs);
		}
		return $this->prepend_controls($output);
	}
	
	/**
	 * @access public
	 * @static
	 * @param mixed $field
	 * @param mixed $value. (default: null)
	 * @param array array $attributes. (default: array())
	 * @return void
	 */
	public function password($field, $value = null, array $attrs = array())
	{
		$attrs['type'] = 'password';
		
		return $this->input($field, $value, $attrs);
	}

	
	/**
	 * @access public
	 * @param mixed $field
	 * @param mixed $value. (default: null)
	 * @param array array $attributes. (default: array())
	 * @return void
	 */
	public function button($field, $value = null, array $attrs = array())
	{
		static::$helper->set_button_attrs($value, $attrs)->clean_attrs('button', $attrs);
		return parent::button($field, $value, $attrs);
	}
	
		/**
	 * @access public
	 * @param mixed $field
	 * @param mixed $value. (default: null)
	 * @param array array $attributes. (default: array())
	 * @return void
	 */
	public function submit($field = 'submit', $value = 'Submit', array $attrs = array())
	{
		$attrs['type'] = 'submit';
		return $this->button($field, $value, $attrs);
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function reset($field = 'reset', $value = 'Reset', array $attrs = array())
	{
		$attrs['type'] = 'reset';
		return $this->button($field, $value, $attrs);
	}
	
	
	/**
	 * @access public
	 * @return void
	 */
	public function checkbox($field, $value = null, $checked = null, array $attrs = array())
	{
		return $this->checkbox_and_radio('checkbox', $field, $value, $checked, $attrs);
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function radio($field, $value = null, $checked = null, array $attrs = array())
	{
		return $this->checkbox_and_radio('radio', $field, $value, $checked, $attrs);
	}
		
	/**
	 * Create a typeahead. Templates are supported
	 * Specific arguments:
	 * 	- source: array or std of sources
	 * 	- multiple: multiple typeahead or not (needs: https://github.com/twitter/bootstrap/pull/2007)
	 *	- items: nulber of items to display
	 * @access public
	 * @return void
	 */
	public function typeahead($field, $value = null, array $attrs = array())
	{
		static::$helper->add_template($attrs);
		
		foreach ($attrs as $name => $attr)
		{
			switch ($name)
			{
				case 'source':
				$attrs['data-source'] = htmlentities(json_encode((array)$attr));
				break;
				
				case 'multiple':
				$attr == true && $attrs['data-mode'] = $name;
				break;
				
				case 'items':
				$attrs['data-'.$name] = (int)$attr;
				break;
			}
		}
		// Assignel'élément au plugin
		$attrs['data-provide'] = 'typeahead';
		// Prévient l'autocompletion
		$attrs['autocomplete'] = 'off';
		
		static::$helper->clean_attrs('typeahead', $attrs);
		// stocke les props extra avant le néttoyage
		return $this->input($field, $value, $attrs);
	}	
	
	/**
	 * Catch thos functions to append .controls div if needed
	 */
	public function select($field, $values = null, array $options = array(), array $attributes = array())
	{
		$out = parent::select($field, $values, $options, $attributes);
		return $this->prepend_controls($out);
	}
	public function textarea($field, $value = null, array $attributes = array())
	{
		$out = parent::textarea($field, $values, $attributes);
		return $this->prepend_controls($out);
	}


	// --------------------------------------------------------------------
	// INTERNAL METHODS
	// --------------------------------------------------------------------

	/**
	 * Shared routine for checkboxes au radios.
	 * if a label key is found, we wrap the input into the label element.
	 * We also check for an opened .control div
	 * 
	 * @access protected
	 * @return void
	 */
	protected function checkbox_and_radio($type, $field, $value, $checked, $attrs)
	{
		static::$helper->add_template($attrs);
		
		if (array_key_exists('label', $attrs))
		{
			$class 	= (array_key_exists('type', $attrs)) ? $type.' '.$attrs['type'] : $type;
			$prep		= $this->prepend_controls();
			$text		= $attrs['label'];

			static::$helper->clean_attrs('checkbox_radio', $attrs);

			$input 	= parent::$type($field, $value, $checked, $attrs);
			$output = $prep."\n".html_tag('label', array('class' => $class), $input.$text);
		}
		else
		{
			$output = parent::$type($field, $value, $checked, $attrs);
		}
		return $output;
	}

	/**
	 * Shared routine for line_open and fieldset_open.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $who
	 * @param mixed $attrs
	 * @return void
	 */
	protected function control_fieldset_open($attrs)
	{
		// String can be passed
		is_string($attrs) and $attrs = array('state' => $attrs);
		
		static::$helper->add_template($attrs);
		
		// generate classes
		$class = array('control-group');
		
		if (array_key_exists('state', $attrs))
		{
			$class[] = $attrs['state'];
		
		}
		static::$helper
			->merge_classes($attrs, $class)
			->clean_attrs('fieldset_line_open', $attrs);
		
		return $attrs;
	}
	
	/**
	 * Build shared properties for generic input element
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _input($field, $value, $attrs)
	{
		$class 		= array();
		$iclass 	= array();
		$surround	= array('prepend' => '', 'append' => '');
		
		// Build the icon if needed
		$prep_app = function($str)
		{
			return (strpos($str,'icon') === 0) ? html_tag('i', array('class' => $str), '') : $str;
		};
		
		static::$helper->add_template($attrs);
		
		foreach ($attrs as $name => $attr)
		{
			switch ($name)
			{
				case 'state':
				$class[] = $attr;
				$attr === 'disabled' and $attrs['disabled'] = 'disabled';
				break;
				
				case 'size':
				$class[] = 'input-'.$attr;
				break;
				
				case 'prepend':
				case 'append':
				$iclass[]	= 'input-'.$name;
				// Is html or not ?: very basic check to maintain script prefs
				$surround[$name] = (strpos($attr, '<') === 0)
					? $attr
					: html_tag('span', array('class' => 'add-on'), $prep_app($attr));
				break;
				
			}
		}
		static::$helper->merge_classes($attrs, $class)->clean_attrs('input', $attrs);
		
		// get the input
		$output = parent::input($field, $value, $attrs);
		
		// surround if needed
		if ($iclass)
		{
			$content= $surround['prepend'].$output.$surround['append'];
			$output	= html_tag('div', array('class' => implode(' ', $iclass)), $content);
		}
		return $output;
	}
	
	
	/**
	 * If we are in line_open() context, prepend the .controls.
	 * The div well be closed by calling line_close()
	 * 
	 * @access protected
	 * @param string $out (default: '')
	 * @return void
	 */
	protected function prepend_controls($out = '')
	{
		// Check if we are in 'line' context and if the control div has been added
		if ($this->line_opened === true && $this->controls_opened === false)
		{
			$out = '<div class="controls">'."\n".$out;
			$this->controls_opened = true;
		}
		return $out;
	}
	
}