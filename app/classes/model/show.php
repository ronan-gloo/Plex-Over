<?php

class Model_Show extends Model_Metadata {
	
	protected static $_type = 'show';
	protected static $_has_many = array(
		'seasons' => array(
			'key_to' => 'parent_id',
			'conditions' => array('order_by' => array('index' => 'asc'))
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