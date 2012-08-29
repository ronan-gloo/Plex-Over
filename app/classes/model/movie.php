<?php

class Model_Movie extends Model_Metadata {
	
	protected static
		$_type = 'movie',
		$_has_one = array('media' => array('model_to' => 'Model_Media', 'key_to' => 'metadata_item_id'));
		
		
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