<?php


/**
 * View_Sidebar class.
 * 
 * @extends ViewModel
 */
class View_Sidebar extends ViewModel {
		
	public $_view			= 'layouts.sidebar';
	public $active		= array('library' => 0, 'plugins' => 0);
	public $icons			= array(
		'movie'		=> 'film',
		'show'		=> 'facetime-video',
		'artist'	=> 'music',
		'photo'		=> 'picture'
	);
	
	
	/**
	 * @access public
	 * @return void
	 */
	public function view()
	{
		// Get the library's sections
		$this->sections = array(__('app.library'));
		
		// Check if active is set
		$active		= $this->active['library'];
		$sections	= Model_Section::find()->order_by('name', 'asc')->get();
		
		foreach ($sections as $section)
		{
			$this->sections[] = array(To::section($section), $section->name, array('icon' => $this->icons[$section->type()]), $active === $section->id);
		}
		
		//$this->sections[] = __('app.plugins');
	}
	
}