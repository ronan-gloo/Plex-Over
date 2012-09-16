<?php

/**
 * Class to interact and build urls with Plex Media Server
 * 
 */
class Plex {
	
	
	/**
	 * PMS Location
	 * 
	 * (default value: null)
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $base_url = null;
	
	/**
	 * LOad configs and set the base url of the plex server.
	 * 
	 * @access public
	 * @static
	 * @param array $config
	 * @return void
	 */
	public static function _init()
	{
		if (is_null(self::$base_url))
		{
			$conf = Config::get('main');
			static::$base_url = $conf['protocol'].'://'.$conf['host'].':'.$conf['port'].'/';
		}
		if (! Config::get('transcode'))
		{
			Config::load('transcode', true);
		}
	}
	
	
	/**
	 * Alias.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function request_driver()
	{
		return 'curl';
	}
	
	/**
	 * Plex media server base url.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function base_url()
	{
		return static::$base_url;
	}
	
	
	/**
	 * Link to transcode video.
	 * 
	 * @access public
	 * @static
	 * @param Model_Part $part
	 * @return void
	 */
	public static function video(Model_Media_Part $part, array $params = array(), $tpath = 'm3u8')
	{
		// merge parameters with default parameters
		$conf = Config::get('transcode');
		
		$params = $conf[$tpath]['params'] + $params;
		
		if ($tpath == 'm3u8')
		{
			$params['quality'] = Config::get('main.'.$tpath.'_quality');
		}
		
		$params['ratingKey']	= $part->media->id;
		$params['url']				= static::$base_url.$part->path();
		
		$turl = $conf[$tpath]['url'].http_build_query($params);
		$now	= time();
		$hash = hash_hmac('sha256', '/'.$turl.'@'.$now, base64_decode($conf['private_key']), true);
		
		$params['X-Plex-Access-Key']	= $conf['public_key'];
		$params['X-Plex-Access-Time']	= $now;
		$params['X-Plex-Access-Code']	= base64_encode($hash);
		//$params['X-Plex-Token']				= Session::get('auth_token');
		
		return static::$base_url.$conf[$tpath]['url'].http_build_query($params);
	}
	
	
	/**
	 * Get the url of a transcode element.
	 * 
	 * @access public
	 * @static
	 * @param mixed $index
	 * @return void
	 */
	public static function video_transcode_url()
	{
		return static::$base_url.'video/:/transcode/segmented/';
	}
		
	/**
	 * Returns the full size image.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function img($media, array $attrs = array())
	{
		$img	= isset($media->part) ? $media->part : $media;
		$segs = explode('.', $img->file);
		$ext	= array_pop($segs);
		
		return Html::img(self::$base_url.'library/parts/'.$img->id.'/file.'.$ext, $attrs);
	}
	
	
	/**
	 * Returns plex generated thumb or art.
	 * 
	 * @access public
	 * @static
	 * @param mixed $media
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public static function thumb($id, array $attrs = array())
	{
		// transcode url
		return static::$base_url.Config::get('transcode.image').http_build_query($attrs);
	}
	
}