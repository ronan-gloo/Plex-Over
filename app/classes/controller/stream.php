<?php

class Controller_Stream extends Controller_Public {
	
	/**
	 * Get subtitle. A lot to do with opensubtitles api...
	 * 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	public function action_subtitle($b64)
	{
		try {
			$file = file_get_contents(base64_decode($b64));
		}
		catch (ErrorException $e) {
			$file = '1'."\n".'00:00:01,000 --> 00:00:10,000'."\n".__('app.subtitles_not_found');
		}
		if (mb_detect_encoding($file, 'UTF-8', true) === false)
		{
			$file = utf8_encode($file);
		}
		return Response::forge($file);
	}
	
	
	/**
	 * Capture m3u8 streaming requests and stream it
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function action_video($id)
	{
		$ext = Input::extension();
		
		// first call...
		if (is_numeric($id))
		{
			$part = Model_Media_Part::find($id);
			$url	= Plex::video($part);
		}
		// Then we have a session
		else
		{
			$url = Plex::video_transcode_url().implode('/', func_get_args()).'.'.$ext;
		}
		
		// set correct content-type
		switch ($ext)
		{
			case '':
			case 'm3u8':
			header('Content-Type: application/x-mpegURL');
			break;
			
			case 'ts':
			header('Content-Type: video/MP2T');
			break;
		}
		// read the content and send it
		$file = fopen($url, 'r');
		
		while( ! feof($file))
		{
			echo fread($file, 2048);
		}
		
		fclose($file);
		
		exit();
		
	}	
	
	/**
	 * Stream an img.
	 * 
	 * @access public
	 * @param mixed $id
	 * @param mixed $attrs
	 * @return void
	 */
	public function action_image($id, $type = 'thumb')
	{
		// build the url
		$base = (Input::get('width') ?: Input::get('height')) ?: 300;
		$url	= Plex::thumb($id, array('url' => Plex::base_url().Input::get('url').'/'.$type,'width' => $base, 'height' => $base));
		
		try {
			$img = file_get_contents($url);
		}
		catch(ErrorException $e) {
			$img = file_get_contents(DOCROOT.'img/nocover.jpg');
		}
		header('Content-Type: image/jpeg');
		return Response::forge($img);
	}
	
	/**
	 * Stream Audio content
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function action_audio($id)
	{
		$track	= Model_Track::find($id) ?: $this->action_404();
		$part		= $track->part();
		$fp			= fopen($part->url(), "r");
		$et			= md5(serialize(fstat($fp)));
		
		header('Content-Type: audio/'.$track->media->audio_codec);
    header('Content-Disposition: inline; filename="'.$track->title.'"');
    header('Content-length: '.$part->size);
    header('Accept-Ranges: bytes');
    header('X-Pad: avoid browser bug');
    header("Etag: ".$et);
		
		while (!feof($fp))
		{
			echo fread($fp, 65536);
		} 
		
		fclose($fp);
		
		exit();
	}

}