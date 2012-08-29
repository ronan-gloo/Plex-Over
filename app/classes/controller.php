<?php


/**
 * Bass controller for all, except Controller_Login.
 * it check if user is authenticated, load langs and config, and instanciate partials for the UI
 * 
 * @extends Fuel\Core\Controller
 */
abstract class Controller extends Fuel\Core\Controller {
	
	private $_view;

	/**
	 * We'll need before() function for our app.
	 * So run init() for user controllers instead.
	 * 
	 * @access public
	 * @return void
	 */
	final public function before()
	{
		// Not logged in
		if (! Auth::check())
		{
			Cookie::set('redirect', Uri::current());
			Response::redirect('login', 'location', 401);
		}
		
		// Set Language
		Config::set('language', Config::get('main.language'));
		
		// Load Gloal lang
		Lang::load('app', 'app');
		// Load library lang
		Lang::load('library');

		$this->_view					= ViewModel::forge('application');
		$this->_view->sidebar	= ViewModel::forge('sidebar');
		$this->_view->header	= ViewModel::forge('header');
				
		return $this->init();
	}
	
	
	/**
	 * Get the Main App view.
	 * 
	 * @access protected
	 * @final
	 * @return void
	 */
	final protected function ui()
	{
		return $this->_view;
	}
	
	
	/**
	 * Return the main template
	 * 
	 * @access public
	 * @param int $status (default: 200)
	 * @return void
	 */
	public function render($status = 200)
	{
		return Response::forge($this->_view, $status);
	}
	
	
	/**
	 * Alias to HttpNotFoundException.
	 * 
	 * @access public
	 * @return void
	 */
	public function action_404()
	{
		throw new HttpNotFoundException;
	}
	
	
	/**
	 * replace before() with init() for child classes.
	 * 
	 * @access public
	 * @return void
	 */
	public function init(){}

	
}