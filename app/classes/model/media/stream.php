<?php

class Model_Media_Stream extends Model {
	
	protected static $_server_path = 'library/streams/';
	protected static $_types = array(
		1 => 'video',
		2 => 'audio',
		3 => 'subtitle',
	);
	
	protected static $_properties = array(
		"id",
		"stream_type_id",
		"media_item_id",
		"url",
		"codec",
		"language",
		"created_at",
		"updated_at",
		"index",
		"media_part_id",
		"channels",
		"bitrate",
		"url_index",
		'default',
		'forced',
		'extra_data'
	);
	
	protected static $_belongs_to = array(
		'part' => array(
			'model_to' 	=> 'Model_Media_Part',
			'key_from'	=> 'media_part_id'
		)
	);
}