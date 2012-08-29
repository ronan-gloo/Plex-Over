<?php

abstract class Controller_Public extends Controller {
	
	protected
		$_order_by 		= array(),				// fields for order_by query
		$_search_by 	= array('title'),	// fields for search form query
		$_per_page 		= 20,							// items per pages fallback
		$_total_items = 0,							// total items (set after query)
		$params				= null;						// $_Get params
	
	/**
	 * 
	 * @access protected
	 * @return void
	 */
	protected function set_sorter()
	{
		// Check for sort args, and generate dropdown if needed
		if (is_array($this->_order_by) and count($this->_order_by) > 0)
		{
			$params			= $this->get_params();
			$sorterby		= $this->order_by();
			$sorterdir	= $this->order_dir();
	
			// Main Link
			$aparam	= array('d' => $sorterdir === 'asc' ? 'desc' : 'asc') + $params;
			$text		= html_tag('small', array(), __('app.sort_by')).' '.strtolower(__($sorterby));
			$anchor = Uri::current().'?'.http_build_query($aparam);
			$items	= array();
			// Dropdown items
			foreach ($this->_order_by as $key => $val)
			{
				if ($val !== $sorterby)
				{
					$params['s'] = $key;
					$items[] = array(Uri::current().'?'.http_build_query($params), __($val));
				}
			}
			
			// Build Dropdown
			$output['drop'] = Html::dropdown_button($text, $items, array(
				'status'		=> 'inverse',
				'title'			=> strip_tags($text).' '.__($sorterdir),
				'split' 		=> true,
				'pull'			=> 'right'
			), 'li');
			$output['dir'] = html_tag('li', array(), Html::anchor($anchor, __($sorterdir)));
			
			return $output;
		}
		return false;
	}
	
	
	/**
	 * Get url parameters and filter them.
	 * 
	 * @access public
	 * @return void
	 */
	public function get_params()
	{
		$this->params = array_filter(array(
			'd' => Input::get('d'),
			's' => Input::get('s'),
			'p' => Input::get('p'),
			'q' => Input::get('q'),
		));
		return $this->params;
	}
	
	/**
	 * Check $_Get Params and user defined order_by.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function order_by()
	{
		foreach ($this->_order_by as $param => $row)
		{
			if (Input::get('s') === $param) return $row;
		}
		return 'title';
	}
	
	
	/**
	 * Build pager.
	 * 
	 * @access protected
	 * @param mixed $item
	 * @param mixed $method
	 * @return void
	 */
	protected function pager($item, $method)
	{
		$pager = View::forge('layouts.pager', array(
			'item' 	=> $item,
			'to'		=> $method,
			'params'=> array('order' => $this->order_by(), 'direction' => $this->order_dir()),
			'get'		=> '?'.http_build_query($this->get_params())
		));
		return $pager;
	}
	
	/**
	 * Get the order direction, and fallback to 'asc' if not found.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function order_dir()
	{
		return Input::get('d') ?: 'asc'; 
	}
	
	
	/**
	 * Finalize the query with ordering, sorting and pagination parameters.
	 * 
	 * @access protected
	 * @param Orm\Query $query
	 * @return Query Results
	 */
	protected function query_get(Orm\Query $query)
	{		
		if ($where = Input::get('q'))
		{
			foreach ($this->_search_by as $field) $query->where($field, 'like', "%$where%");
		}
		
		$this->_total_items = $query->count();
		
		$per_page = Config::get('main.per_page') ?: $this->_per_page;
		
		Pagination::set_config(array(
			'uri_parameter' => 'p',
			'per_page' 			=> $per_page,
			'total_items'		=> $this->_total_items
		));
		
		$this->ui()->set('pagination', Pagination::create_links());

		$query->order_by($this->order_by(), $this->order_dir())
			->limit($per_page)
			->offset(Pagination::offset());
		
		return $query->get();
	}
	
	/**
	 * Add required assets for video player.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function add_video_player()
	{
		Asset::instance()
			->css('http://vjs.zencdn.net/c/video-js.css', array(), 'local')
			->js(array('http://vjs.zencdn.net/c/video.js', 'video.js'), array(), 'local');
	}
	
	protected function add_audio_player()
	{
		Asset::instance()->js(array('soundmanager2.js', 'audio.js'), array(), 'local');
	}
	
}