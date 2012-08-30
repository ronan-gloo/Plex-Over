<?php

/**
 * Controller For Videos Library section type.
 * 
 * @extends Controller_Section
 */
class Controller_Movies extends Controller_Section {
	
	protected $_order_by = array(
		'y' => 'year',
		't' => 'title',
		'a'	=> 'added_at',
		'r'	=> 'rating'
	);
	
	/**
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	public function action_section($id = null)
	{
		$data['section']	= Model_Section::find($id);
		$query						= Model_Movie::find()->where('library_section_id', $id);
		$data['movies']		= $this->query_get($query);
		$data['total']		= $this->_total_items;
		
		$ui = $this->ui();
		$ui->set_active('library', $data['section']);
		
		$ui->header->sorter = $this->set_sorter();
		$ui->breadcrumb			= array(array('#', $data['section']->name));
		$ui->content				= View::forge('movies.index', $data);
		
		return $this->render();
	}
	
	
	/**
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	public function action_movie($id = null)
	{
		$movie = Model_Movie::find($id);
		
		$ui = $this->ui();
		$ui->set_active('library', $movie->section);
		
		$ui->breadcrumb = array(
			array(To::section($movie->section), $movie->section->name),
			array('#', $movie->title),
		);
		
		$this->add_video_player();
		$this->get_params();
		$this->set_pager($movie, 'movie');

		$data['movie']	= $movie;
		
		$ui->content = View::forge('movies.single',$data);
		
		return $this->render();
	}
	
}