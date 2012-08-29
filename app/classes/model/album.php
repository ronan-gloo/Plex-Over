<?php


/**
 * An album is not a media, so extends to Metadata.
 * 
 * @extends Model_Media
 */
class Model_Album extends Model_Metadata {
	
	// object properties
	protected static $_properties = array(
		'id',
		'metadata_type',
		'library_section_id',
		'parent_id',
		'title',
		'title_sort',
		'original_title',
		'summary',
		'tags_genre',
		'created_at',
		'updated_at',
		'year'
	);
	
	protected static $_belongs_to = array(
		'artist'	=> array('key_from' => 'parent_id')
	);
	
	protected static $_has_many = array(
		'tracks' => array(
			'key_to'		=> 'parent_id',
			'conditions'=> array('order_by' => array('index' => 'asc'))
		)
	);
}