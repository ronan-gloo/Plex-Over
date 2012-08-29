<?php

class Model_Section extends \Model {
	
	// base object url for server
	protected static
		$_server_path = 'library/sections/';

	protected static
		$_has_many		= array(),
		$_table_name	= 'library_sections',
		$_properties	= array(
			'id',
			'library_id',
			'name',
			'name_sort',
			'section_type',
			'language',
			'created_at',
			'updated_at'
	);
		
	/**
	 * Named Types of libraries
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $types = array(
		1 	=> 'movie',
		2 	=> 'show',
		8 	=> 'artist',
		13 	=> 'photo',
	);
	
	/**
	 * Return named type of section.
	 * 
	 * @access public
	 * @return void
	 */
	public function type()
	{
		if (array_key_exists($this->section_type, static::$types))
		{
			return static::$types[$this->section_type];
		}
	}
	
	
	/**
	 * Set the correct relation in regard of section type.
	 * 
	 * @access public
	 * @return void
	 */
	public function _event_after_load()
	{
		$type = $this->type();
		
		switch($type)
		{
			case 'movie':
			case 'show':
			$type = 'Video';
			break;
			
			case 'photo':
			$type = 'Picture';
			break;
			
			case 'artist':
			$type = 'Track';
			break;
		}

		// set the model corresponding to the type
		$params = array(
			'model_to'=> "Model_$type",
			'key_to' 	=> 'library_section_id'
		);
		// Add Relation
		static::$_has_many['medias'] = $params;
	}
	
}