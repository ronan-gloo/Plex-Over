<?php

class Model_Season extends Model_Metadata {
	
	protected static
		$_type				= 'season',
		$_belongs_to	= array('show' => array('key_from' => 'parent_id')),
		$_has_many		= array('episodes'	=> array(
			'key_to' => 'parent_id',
			'conditions' => array('order_by' => array('index' => 'asc'))
		));
	
	
	/**
	 * Format season title with index.
	 * 
	 * @access public
	 * @return void
	 */
	public function index_title()
	{
		return __('app.season').' '.$this->index;
	}	
}