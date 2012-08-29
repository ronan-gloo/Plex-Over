<?php

abstract class Model_Metadata extends \Model {
	
	// db table name
	protected static $_table_name = 'metadata_items';
	
	// base object url for server
	protected static $_server_path = 'library/metadata/';
	
	// Type of metadata model (required, or exception)
	protected static $_type = null;
	
	// allowed types
	protected static $_types = array(
		1		=> 'movie',		// media
		2		=> 'show',		// metadata
		3		=> 'season',	// metatdata
		4		=> 'episode',	// media
		8		=> 'artist',	// metadata
		9		=> 'album',		// metadata
		10	=> 'track',		// media
		13	=> 'photo'	// media
	);
		
	protected static $_properties = array(
		'id',
		'library_section_id',
		'parent_id',
		'metadata_type',
		'guid', // used for relationship with settings
		'media_item_count', // Medias count
		'title',
		'title_sort',
		'original_title',
		'studio',
		'rating',
		'rating_count',
		'tagline',
		'summary',
		//'trivia',
		//'quotes',
		'content_rating',
		'content_rating_age',
		'index',
		//'absolute_index',
		'duration',
		//'user_thumb_url',
		//'user_art_url',
		//'user_banner_url',
		//'user_music_url',
		//'user_fields',
		'tags_genre',
		'tags_collection',
		'tags_director',
		'tags_writer',
		'tags_star',
		'originally_available_at',
		//'available_at',
		//'expires_at',
		//'refreshed_at',
		'year',
		'added_at',
		'created_at',
		'updated_at',
		'deleted_at',
		//'tags_country',
		//'extra_data'
	);
	/**
	 * Check for $_type property before to run.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function _init()
	{
		// try to guess the type according the model name
		if (is_null(static::$_type))
		{
			$name = explode('_', strtolower(get_called_class()));
			static::$_type = array_pop($name);
		}
		// throw an exception if not a valid type
		if (! in_array(static::$_type, static::$_types))
		{
			throw new PlexOverException('Model '.get_called_class().' has no valid $_type property');
		}
		// because we use named types
		static::$_type = array_search(static::$_type, static::$_types);
		
		// Every metada belongs to a section, so relate it by default
		static::$_belongs_to['section'] = array('key_from' => 'library_section_id');
		
		// Set the relation with metadata_item_settings for episodes and movies
		if (static::$_type === 1 or static::$_type === 4)
		{
			static::$_has_one['setting'] = array(
				'model_to'	=> 'Model_Metadata_Setting',
				'key_from'	=> 'guid',
				'key_to'		=> 'guid'
			);
		}
	}
	
	/**
	 * Hook query to force type where condition.
	 * 
	 * @access public
	 * @static
	 * @param array $options (default: array())
	 * @return void
	 */
	public static function query($options = array())
	{
		return Orm\Query::forge(get_called_class(), static::connection(), $options)
		->where('metadata_type', static::$_type);
	}

	/**
	 * Get the current season view offset by calculation total episodes duration - total episodes view_offset
	 * 
	 * @access public
	 * @param bool $percent (default: true) : Return % or not
	 * @return Int
	 */
	public function progress($property = null)
	{
		// Loop throught a sub property ?
		$items = is_string($property) ? $this->$property : array($this);	
		
		$duration = 0;
		$offset		= 0;
		
		foreach ($items as $item)
		{
			// check if item as been already viewed and not in progress
			if ($item->setting and $item->setting->view_count > 0 and ! $item->setting->view_offset)
			{
				$offset = $duration = 1;
				break;
			}
			
			$duration += intval($item->media->duration);
			
			// get the item offset
			$offset	+= intval($item->setting ? $item->setting->view_offset : 0);
		}
		
		$percent	= Num::to_percent($offset, $duration);
		$status		= ($percent == 100) ? 'success' : '';
		
		return Html::progress($percent, $status);
	}

	/**
	 * Get the metadata duration.
	 * 
	 * @access public
	 * @return void
	 */
	public function duration()
	{
		return $this->duration;
	}
	
	
	/**
	 * Returns formated summary.
	 * 
	 * @access public
	 * @return void
	 */
	public function summary()
	{
		return Text::to_html($this->summary);
	}
	
	
	/**
	 * @access public
	 * @return void
	 */
	public function index_title()
	{
		return $this->title;
	}
	
		/**
	 * Prev ep.
	 * 
	 * @access public
	 * @return void
	 */
	public function prev(array $params = array())
	{
		$params['dir'] = '<';
		return $this->prev_next($params);
	}
	
	/**
	 * Next ep.
	 * 
	 * @access public
	 * @return void
	 */
	public function next(array $params = array())
	{
		$params['dir'] = '>';
		return $this->prev_next($params);
	}

	/**
	 * @access protected
	 * @param mixed $dir
	 * @return void
	 */
	protected function prev_next($params)
	{
		return self::find()
			->where('metadata_type', $this->metadata_type)
			->where('library_section_id', $this->library_section_id)
			->where('parent_id', $this->parent_id)
			->where('index', $params['dir'], $this->index)
			->order_by('index', $params['dir'] === '<' ? 'desc' : 'asc')
			->get_one();
	}
	
	/**
	 * Retunr preformatted tags.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param string $separator (default: ')
	 * @param mixed '
	 * @return void
	 */
	public function tags($key, $separator = ', ')
	{
		return str_replace('|', $separator, $this->{'tags_'.$key});
	}

}