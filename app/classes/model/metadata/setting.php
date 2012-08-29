<?php

class Model_Metadata_Setting extends Orm\Model {
	
	protected static $_table_name = 'metadata_item_settings';
	protected static $_properties = array(
		'id',
		'account_id', 	// user account
		'guid', 				// relatioship key
		'view_offset', 	// resume play
		'view_count', 	// num of views,
		'last_viewed_at',
		'created_at',
		'updated_at'
	);
	
	protected static $_belongs_to = array('metadata');
	
	
	
}