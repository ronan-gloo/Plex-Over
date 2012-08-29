<?php

class Model_Photo extends Model_Metadata {
	
	protected static
		$_type = 'photo',
		$_has_one = array('media' => array('model_to' => 'Model_Media', 'key_to' => 'metadata_item_id'));
	
}