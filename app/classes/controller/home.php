<?php

/**
 * Home page controller...
 * 
 * @extends Controller_Public
 */
class Controller_Home extends Controller_Public {
	
	
	/**
	 * Display latests movies, albums, and tv show ordered by added date.
	 * 
	 * @access public
	 * @return void
	 */
	public function action_index()
	{
		$q = function($query) {
			return $query->order_by('added_at', 'desc')->limit(5)->get();
		};
		
		$data['movie']	= array('height' => 180, 'items' => $q(Model_Movie::find()));
		$data['season']	= array('height' => 180, 'items' => $q(Model_Season::find()));
		$data['album']	= array('height' => 140, 'items' => $q(Model_Album::find()));
			
		$this->ui()->content = View::forge('home.loop', array('data' => $data));
		
		return $this->render();
	}
	
}