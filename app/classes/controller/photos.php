<?php

class Controller_Photos extends Controller_Section {
	
	protected $_order_by = array(
		't' => 'title',
		'a'	=> 'added_at',
		'y' => 'year'
	);

	public function action_section($id = null)
	{
		$data['section'] = Model_Section::find($id) ?: $this>action_404();
		$query = Model_Photo::find()->where('library_section_id', $id);
		
		$data['photos']	= $this->query_get($query);
		$data['total']	= $this->_total_items;

		$ui = $this->ui();
		$ui->set_active('library', $data['section']);
		
		Asset::js('jquery.fancybox.js', array(), 'local');
		Asset::css('jquery.fancybox.css', array(), 'local');
			
		$ui->header->sorter	= $this->set_sorter();
		$ui->breadcrumb			= array(array('#', $data['section']->name));
		$ui->content				= View::forge('photos.index', $data);

		return $this->render();
	}
	
	
}