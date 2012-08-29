<?php



class Model_Media_Part extends \Model {
	
	protected static
		$_table_name	= 'media_parts',
		$_server_path	= 'library/parts/',
		$_properties	= array(
			'id',
			'media_item_id',
			'open_subtitle_hash',
			'hash',
			'index', // index of the parts set
			'file', // full path to the file
			'size',
			'created_at',
			'updated_at',
			'duration',
		);
	
	protected static $_belongs_to = array(
		'media' => array(
			'model_to' => 'Model_Media',
			'key_from' => 'media_item_id'
		)
	);
	
	protected static $_has_one = array(
		'subtitle' => array(
			'model_to' 		=> 'Model_Media_Stream',
			'key_to'			=> 'media_part_id',
			'conditions'	=> array(
				'where' => array('stream_type_id' => '3')
			)
		)
	);
	
	/**
	 * Path.
	 * 
	 * @access public
	 * @return void
	 */
	public function path()
	{
		return self::$_server_path.$this->id.'/file.'.$this->media->container;
	}
	
	
	/**
	 * Check if a subtitle exists, and return the path.
	 * 
	 * @access public
	 * @return String url
	 */
	public function subtitle()
	{
		if ($this->subtitle and $this->subtitle->url)
		{
			$file = $this->subtitle->server_url();
		}
		else
		{
			$file = explode('.', $this->file);
			array_pop($file) and $file[] = 'srt';
			$file = implode('.', $file);
			$file = (file_exists($file) and is_file($file)) ? $file : null;
		}
		return $file;
	}
	
	/**
	 * Url
	 * 
	 * @access public
	 * @return void
	 */
	public function url()
	{
		return Plex::base_url().$this->path();
	}
	
}