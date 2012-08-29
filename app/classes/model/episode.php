<?php

class Model_Episode extends Model_Metadata {
	
	protected static $_type				= 'episode';
	protected static $_belongs_to = array(
		'season' => array('key_from' => 'parent_id')
	);
	protected static $_has_one = array(
		'media' => array('model_to' => 'Model_Media', 'key_to' => 'metadata_item_id')
	);	
	
	/**
	 * Display localized episode index.
	 * 
	 * @access public
	 * @return void
	 */
	public function index_title()
	{
		return __('app.episode').' '.$this->index;
	}	
}