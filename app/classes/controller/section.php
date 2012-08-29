<?php


/**
 * All controllers that are section based should extends this class
 * in order to allow the refresh functionnality
 * 
 * @abstract
 * @extends Controller_Public
 */
abstract class Controller_Section extends Controller_Public {
	
	/**
	 * Update a Section.
	 * 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	public function action_refresh($id = null)
	{
		$section = Model_Section::find($id) ?: $this->action_404();
		$url = $section->server_url().'/refresh';
		
		// Request an update
		$req = Request::forge($url, Plex::request_driver())->execute();
		
		return Response::forge($section->updated_at);
	}
	
	
	/**
	 * action_refresh() retunrs the prev "updated_at' timestamp of the $id section.
	 * We return false unless the new timestamp is not >
	 * 
	 * @access public
	 * @return void
	 */
	public function action_refresh_status($id = null)
	{
		$section	= Model_Section::find($id) ?: $this->action_404();
		$response = simplexml_load_file(Plex::base_url().'library/sections');
		
		foreach($response->children() as $child)
		{
			$attrs = $child->attributes();
			
			if ((string)$attrs->key == $section->id and (int)$attrs->refreshing == 1)
			{
				return Response::forge($attrs->refreshing);
			}
		}
		return Response::forge(0);
	}
	
	
}