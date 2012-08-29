<?php

/**
 * An album is not a media, so extends to Metadata.
 * 
 * @extends Model_Media
 */
class Model_Artist extends Model_Metadata {
	
	protected static $_type = 'artist';
	// object properties
	protected static $_properties = array(
		'id',
		'metadata_type',
		'library_section_id',
		'title',
		'title_sort',
		'parent_id',
		'original_title',
		'summary',
		'tags_genre',
		'created_at',
		'updated_at'
	);
	
	protected static $_has_many = array(
		'albums' => array(
			'key_to'		=> 'parent_id',
			'conditions'=> array('order_by'	=> array('year' => 'desc'))
		)
	);
	
	/**
	 * Override parent method
	 * @access protected
	 * @param mixed $dir
	 * @return void
	 */
	protected function prev_next($params)
	{
		$query = self::find()
			->where('metadata_type', $this->metadata_type)
			->where('library_section_id', $this->library_section_id)
			->where($params['order'], $params['dir'], $this->{$params['order']})
			->order_by($params['order'], $params['dir'] === '<' ? 'desc' : 'asc');
			
		return $query->get_one();
	}
	
}