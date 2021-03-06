<?php


/**
 * Controller For Shows Library section type.
 * 
 * @extends Controller_Section
 */
class Controller_Shows extends Controller_Section {
	
	protected $_order_by = array(
		't' => 'title',
		'y' => 'year'
	);
	
	/**
	 * Home.
	 * 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	public function action_section($id = null)
	{
		$data['section']	= Model_Section::find($id) ?: $this->action_404();
		
		$query = Model_Show::find()->where('library_section_id', $id);
		
		$data['shows'] = $this->query_get($query);
		$data['total'] = $this->_total_items;

		$ui = $this->ui();
		$ui->set_active('library', $data['section']);
		
		$ui->header->sorter	= $this->set_sorter();
		$ui->breadcrumb->add('#', $data['section']->name);
		$ui->content				= View::forge('shows.index', $data);
		
		return $this->render();
	}
	
	
	/**
	 * Get a show and display seasons.
	 * 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	public function action_show($id = null)
	{
		$show = Model_Show::find($id) ?: $this->action_404();
		
		$ui = $this->ui();
		$ui->set_active('library', $show->section);
		
		$ui->breadcrumb
			->add(To::section($show->section), $show->section->name)
			->add('#', $show->title);
		
		$ui->content = View::forge('shows.show', array('show' => $show));
		
		$this->set_pager($show, 'show');
		
		return $this->render();
	}
	
	
	/**
	 * Season with episodes.
	 * 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	public function action_season($id = null)
	{
		$season = Model_Season::find($id);
		
		$ui = $this->ui();
		$ui->set_active('library', $season->section);
		
		$ui->breadcrumb
			->add(To::section($season->section), $season->section->name)
			->add(To::show($season->show), $season->show->title)
			->add('#', __('app.season').' '.$season->index);
		
		$ui->content = View::forge('shows.season', array('season'	=> $season));
		
		$this->set_pager($season, 'season');
		
		return $this->render();
	}
	
	
	/**
	 * Episode main page.
	 * 
	 * @access public
	 * @return void
	 */
	public function action_episode($id = null)
	{
		$episode = Model_Episode::find($id);
		
		$ui = $this->ui();
		$ui->set_active('library', $episode->section);
		
		$ui->breadcrumb
			->add(To::section($episode->section), $episode->section->name)
			->add(To::show($episode->season->show), $episode->season->show->title)
			->add(To::season($episode->season), __('app.season').' '.$episode->season->index)
			->add('#', $episode->title);
		
		$this->add_video_player();
		
		$ui->content = View::forge('shows.episode', array(
			'episode' => $episode,
			'video'		=> $episode->media,
		));
		
		$this->set_pager($episode, 'episode');

		return $this->render();
	}
	
}