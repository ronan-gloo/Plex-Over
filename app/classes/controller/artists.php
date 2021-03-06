<?php


/**
 * All Sections with type artists are managed here.
 * 
 * @extends Controller_Section
 */
class Controller_Artists extends Controller_Section {
	
	protected $_order_by = array(
		't' => 'title',
		'a'	=> 'added_at'
	);
	
	/**
	 * Home page with artists list
	 * 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	public function action_section($id = null)
	{
		// get the section
		$data['section'] = Model_Section::find($id);
		
		// get the section artist
		$query = Model_Artist::find()->where('library_section_id', $id);
		
		$data['artists']	= $this->query_get($query);
		$data['total']		= $this->_total_items;

		$ui = $this->ui()->set_active('library', $data['section']);

		$ui->breadcrumb->add('#', $data['section']->name);
		$ui->header->sorter = $this->set_sorter();
		$ui->content				= View::forge('artists.index', $data);

		return $this->render();
	}
	
	
	/**
	 * Page for an album
	 * 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	public function action_album($id = null)
	{
		$album = Model_Album::find($id);
		
		$ui = $this->ui();
		
		$ui->set_active('library', $album->section);
		
		$ui->breadcrumb
			->add(To::section($album->section), $album->section->name)
			->add(To::artist($album->artist), $album->artist->title)
			->add('#', $album->title);
		
		$ui->content = View::forge('artists.album', array('album' => $album));
		
		$this->add_audio_player();
		
		return $this->render();
	}
	
	/**
	 * Artist page, with all albums
	 * 
	 * @access public
	 * @return void
	 */
	public function action_artist($id = null)
	{
		$artist = Model_Artist::find($id);
		
		$ui = $this->ui()->set_active('library', $artist->section);
		
		$ui->breadcrumb
			->add(To::section($artist->section), $artist->section->name)
			->add('#', $artist->title);
		
		$ui->content = View::forge('artists.artist', array('artist'	=> $artist));

		$this->set_pager($artist, 'artist');

		return $this->render();
	}
	
	
}