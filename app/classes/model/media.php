<?php

class Model_Media extends \Model {
	
	protected static $_table_name = 'media_items';
	
	// All media object properties: specific properties are defined in child classes
	protected static $_properties = array(
		'id',
		'library_section_id',
		'section_location_id',
		'metadata_item_id',
		'width',
		'height',
		'size',
		'duration',
		'bitrate',
		'container',
		'video_codec',
		'audio_codec',
		'display_aspect_ratio',
		'frames_per_second',
		'audio_channels',
		'interlaced',
		'source',
		'hints',
		'display_offset',
		'settings',
		'optimized_for_streaming',
		'deleted_at',
		'created_at',
		'updated_at',
		'media_analysis_version',
		'sample_aspect_ratio',
		'extra_data'
	);
		
	/**
	 * @access public
	 * @static
	 * @return void
	 */
	public static function _init()
	{
		// set the shared relations
		static::$_belongs_to['section'] = array();
		
		// Always many parts
		static::$_has_many['parts'] = array(
			'model_to'=> 'Model_Media_Part',
			'key_to'	=> 'media_item_id'
		);
	}
	
}