<?php

namespace Bootstrap;

class Pagination extends \Fuel\Core\Pagination {
	
	
	/**
	 * Le parametre $_GET à prendre en compte dans la pagination
	 * 
	 * (default value: null)
	 * 
	 * @var mixed
	 * @access public
	 * @static
	 */
	public static $uri_parameter = null;
	
	/**
	 * Parametres de l'url à traiter si besoin
	 * 
	 * (default value: null)
	 * 
	 * @var mixed
	 * @access public
	 * @static
	 */
	protected static
		$uri_parameters = array(),
		$helper = null;
	
	/**
	 * template
	 * 
	 * @var mixed
	 * @access public
	 * @static
	 */
	public static $template = array(
		'wrapper_start'  	  => '<ul class="pagination">',
		'wrapper_end'    	  => ' </ul>',
		'page_start'     	  => '',
		'page_end'       	  => '',
		'previous_start' 	  => '<li class="previous">',
		'previous_end'   	  => ' </li>',
		'previous_inactive_start' => ' <li class="previous active"><a>',
		'previous_inactive_end'   => ' </a></li>',
		'previous_mark'  	  => '&laquo; ',
		'next_start'     	  => '<li class="next"> ',
		'next_end'       	  => ' </li>',
		'next_inactive_start'=> ' <li class="next active"><a>',
		'next_inactive_end' => ' </a></li>',
		'next_mark'      	  => ' &raquo;',
		'active_start'   	  => '<li class="active"><a> ',
		'active_end'     	  => ' </a></li>',
		'regular_start' 		=> '',
		'regular_end' 			=> '',
	);

	/**
		* Transforme $url_params en array si besoin
	 * @access public
	 * @param array   $config The configuration array
	 * @return void
	 */
	public static function set_config(array $config)
	{
		if (isset($config['uri_parameter']))
		{
			$config['uri_parameters'] = \Input::get();
			static::$current_page = \Input::get($config['uri_parameter']) ?: 1;
		}
		parent::set_config($config);
	}
	
	/**
	 * Retourne l'offset calculé par la classe
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function offset()
	{
		return static::$offset;
	}
	
	/**
	 * Creation de l'url avec les parametres $_GET
	 * 
	 * @access protected
	 * @static
	 * @param mixed $base
	 * @param mixed $i
	 * @return void
	 */
	protected static function create_url($url)
	{
		static::$uri_parameters[static::$uri_parameter] = $url ?: 0;
		return static::$pagination_url.'?'.http_build_query(static::$uri_parameters);
	}
	
	// --------------------------------------------------------------------
	/**
	 * Prepares vars for creating links
	 *
	 * @access public
	 * @return array    The pagination variables
	 */
	protected static function initialize()
	{
		static::$total_pages = ceil(static::$total_items / static::$per_page) ?: 1;

		static::$current_page = (static::$total_items > 0 && static::$current_page > 1) ? static::$current_page : (int) \Input::param(static::$uri_parameter);

		if (static::$current_page > static::$total_pages)
		{
			static::$current_page = static::$total_pages;
		}
		elseif (static::$current_page < 1)
		{
			static::$current_page = 1;
		}
		// The current page must be zero based so that the offset for page 1 is 0.
		static::$offset = (static::$current_page - 1) * static::$per_page;
	}

	
	// --------------------------------------------------------------------
	/**
	 * Pagination Page Number links
	 *
	 * @access public
	 * @return mixed    Markup for page number links
	 */
	public static function page_links()
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		$pagination = '';

		// Let's get the starting page number, this is determined using num_links
		$start = ((static::$current_page - static::$num_links) > 0) ? static::$current_page - (static::$num_links - 1) : 1;

		// Let's get the ending page number
		$end   = ((static::$current_page + static::$num_links) < static::$total_pages) ? static::$current_page + static::$num_links : static::$total_pages;

		for($i = $start; $i <= $end; $i++)
		{
			if (static::$current_page == $i)
			{
				$pagination .= static::$template['active_start'].$i.static::$template['active_end'];
			}
			else
			{
				$url = ($i == 1) ? '' : $i;
				$pagination .= '<li>'.\Html::anchor(static::create_url($url), $i).'</li>';
			}
		}

		return static::$template['page_start'].$pagination.static::$template['page_end'];
	}
	
	// --------------------------------------------------------------------
	/**
	 * Creates the pagination links
	 *
	 * @access public
	 * @return mixed    The pagination links
	 */
	public static function create_links()
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		\Lang::load('pagination', true);

		$pagination  = static::$template['wrapper_start'];
		$pagination .= static::prev_link(\Lang::get('pagination.previous'));
		$pagination .= static::page_links();
		$pagination .= static::next_link(\Lang::get('pagination.next'));
		$pagination .= static::$template['wrapper_end'];

		return $pagination;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Pagination "Next" link
	 *
	 * @access public
	 * @param string $value The text displayed in link
	 * @return mixed    The next link
	 */
	public static function next_link($value)
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		if (static::$current_page == static::$total_pages)
		{
			return static::$template['next_inactive_start'].$value.static::$template['next_mark'].static::$template['next_inactive_end'];
		}
		else
		{
			$next_page = static::$current_page + 1;
			return static::$template['next_start'].\Html::anchor(static::create_url($next_page), $value.static::$template['next_mark']).static::$template['next_end'];
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Pagination "Previous" link
	 *
	 * @access public
	 * @param string $value The text displayed in link
	 * @return mixed    The previous link
	 */
	public static function prev_link($value)
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		if (static::$current_page == 1)
		{
			return static::$template['previous_inactive_start'].static::$template['previous_mark'].$value.static::$template['previous_inactive_end'];
		}
		else
		{
			$previous_page = static::$current_page - 1;
			$previous_page = ($previous_page == 1) ? 0 : $previous_page;
			return static::$template['previous_start'].\Html::anchor(static::create_url($previous_page), static::$template['previous_mark'].$value).static::$template['previous_end'];
		}
	}
	
	
	/**
	 * Bootstrap pager.
	 * 
	 * @access public
	 * @static
	 * @param mixed $prev_value
	 * @param mixed $next_value
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public static function pager($prev_value, $next_value, $attrs = array())
	{
		$prev = parent::prev_link($prev_value);
		$next = parent::next_link($next_value);
		
		if (is_null(self::$helper))
		{
			self::$helper = Bootstrap::forge('pagination');
		}
		
		self::$helper->merge_classes($attrs, array('pager'));
		
		return html_tag('ul', $attrs, $prev.$next);
	}
	
}