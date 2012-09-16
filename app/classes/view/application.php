<?php

/**
 * Main View Model Class to maanage application template.
 * 
 * @extends ViewModel
 */
class View_Application extends ViewModel {
	
	/**
	 * Create empty value for pagination var.
	 * 
	 * @access public
	 * @return void
	 */
	public function before()
	{
		$this->pagination = null;
		$this->breadcrumb = Html::breadcrumb();
		$this->breadcrumb->add(Uri::base(), __('app.home'), array('icon' => 'home'));
	}
	
	/**
	 * - Test if there is a content
	 * - Add global assets
	 * - Set page title, css class and breadcrumb
	 * @access public
	 * @return void
	 */
	public function view()
	{
		// check if content var is set...
		if (! isset($this->content))
		{
			throw new PlexOverException('You must set a content before to render the page !');
		}
		
		$group = 'main';
		$param = array();
		$this->assets = Asset::instance();
		
		// Get required assets
		$this->assets->css(array(
			'bootstrap.min.css',
			'style.css'
		), $param, $group);
		
		$this->assets->js(array(
			'jquery.min.js',
			'bootstrap.min.js',
			'application.js'
		), $param, $group);
		
		$this->set_title();
		$this->set_namespace($group);
		
		$this->breadcrumb->render();
	}
	
	
	/**
	 * Set pages title.
	 * 
	 * @access public
	 * @return void
	 */
	public function set_title()
	{
		$title = array(APPNAME);
		
		if ($this->breadcrumb)
		{
			foreach ($this->breadcrumb as $item) $title[] = $item[1];
		}
		else
		{
			$title[] =  __('app.home');
		}
		$this->title = implode(' - ' , $title);
	}
	
	/**
	 * set the page class / id based on module / action
	 * Can be used as css namespace for extra styling rules.
	 * We also add a "paged" class if pagination or pager are rendered
	 * @access public
	 * @return void
	 */
	public function set_namespace($group)
	{
		$this->style = new stdClass;
		$this->style->page_id			= Request::active()->module ?: $group;
		$this->style->page_class 	= Request::active()->action;
		$this->style->page_class .= $this->pagination ? ' paged' : '';
	}	
	
	/**
	 * Set the active in sidebar list.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $val
	 * @return void
	 */
	public function set_active($key, $section)
	{
		if (isset($this->sidebar->active[$key]))
		{
			$this->sidebar->active[$key] = $section->id;
			
			// Push id to header view
			if ($key === 'library')
			{
				$this->header->current_section = $section;
			}
			
		}
		return $this;
	}
}
