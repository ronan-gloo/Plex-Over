<?php

class Controller_Subtitles extends Controller_Public {
	
	protected $part;
	
	/**
	 * Tru to find subtitles throught PMS or Local file.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function action_get($id = 0)
	{
		$this->part = Model_Media_Part::find($id) ?: $this->action_404();
		
		// Part exists: test url to avoid 501 Not implemented error on PMS
		header('Content-Type: text/vtt');
		
		while( ! feof($file))
		{
			echo fread($file, 2048);
		}
		fclose($file);
		
		exit();
	}
	
	/**
	 * Try to get the local subtitle based on filename.
	 * 
	 * @access public
	 * @return void
	 */
	public function find_local()
	{
		$file = explode('.', $this->part->file);
		array_pop($file) and $file[] = 'srt';
		$file = implode('.', $file);
		
		return (file_exists($file) and is_file($file)) ? $file : null;
	}
	
	
	/**
	 * Not implemented yet...
	 * 
	 * @access public
	 * @return void
	 */
	private function _action_download()
	{
		$size = $srt->media->size;
		
		$id = preg_match('!\d+!', $srt->guid, $match);
		$search = array(
			'sublanguageid' => 'fre',
			//'moviebytesize'	=> $size,
			//'query'					=> $srt->title_sort,
			//'SubSumCD'			=> $srt->media_item_count,
			'imdbid' => $match[0] // à parser dans le guid
		);
		$client		= Ripcord::client('http://api.opensubtitles.org/xml-rpc');
		$data			= $client->LogIn('', '', 'fre', 'Os Test User Agent');
		$results	= $client->SearchSubtitles($data['token'], array($search));
		$subs			= array();
		
		foreach ($results['data'] as $sub)
		{
			// Same number of files
			if ($sub['SubSumCD'] == $srt->media_item_count)
			{
				$subs[] = $sub;
			}
		}
		debug($subs);

		$ids = func_get_args();
	}
	
}