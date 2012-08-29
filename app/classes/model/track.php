<?php

class Model_Track extends Model_Metadata {
	
	protected static
		$_type				= 'track',
		$_properties	= array(
			'id',
			'parent_id',
			'title',
			'original_title',
			'duration',
			'index',
			'year'
		);
	
	protected static
		$_belongs_to	= array('album'),
		$_has_one			= array('media' => array('key_to'	=> 'metadata_item_id', 'model_to'	=> 'Model_Media'));
	
	/**
	 * Get the audio part.
	 * 
	 * @access public
	 * @return void
	 */
	public function part()
	{
		return reset($this->media->parts);
	}
	
	
}