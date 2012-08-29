<?php


/**
 * Manage Parts objects downloads, from file system or Plex Media server
 * 
 * @extends Controller_Public
 */
class Controller_Download extends Controller_Public {
	
	/**
	 * Download a part.
	 * First, we try to get the file drectly from file system.
	 * If we can't, we try from PMS.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function action_index($id)
	{
		$part = Model_Media_Part::find($id) ?: $this->action_404();
		
		// Direct download
		try {
			File::download($part->file);
		}
		catch (FileAccessException $e) {
			Log::error($part->file.': '.$e->getMessage());
		}
		// Or PMS
		try {
			$file = fopen($part->url(), 'r');
			$name = Str::pop('/', $part->file);
			header('Content-Type: application/octect-stream');
			header('Content-Disposition: attachment; filename="'.$name.'"');
			header('Content-Description: File Transfer');
			header('Content-Length: '.$part->size);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

			while( ! feof($file))
			{
				echo fread($file, 2048);
			}
			fclose($file);
			exit();
		}
		catch (ErrorException $e) {
			Log::error($part->file.': '.$e->getMessage());
			$this->action_404();
		}
	}
	
}