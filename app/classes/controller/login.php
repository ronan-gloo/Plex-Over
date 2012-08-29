<?php

/**
 * Manage user login and logout.
 * We don't extends to the main Controller class,
 * because Controller check for a logged user and redirect here if not.
 * 
 * @extends Fuel\Core\Controller
 */
class Controller_Login extends Fuel\Core\Controller {
	
	
	/**
	 * Load specific language file for all methods.
	 * 
	 * @access public
	 * @return void
	 */
	public function before()
	{
		Lang::load('login');
	}
	
	/**
	 * Dsiplay login form, with errors if found.
	 * If user is already logged, we go to the home page
	 * 
	 * @access public
	 * @return void
	 */
	public function action_index()
	{
		if (Auth::check())
		{
			Response::redirect(Uri::base());
		}
		
		$view	 = View::forge('login', array(
			'username' 	=> Session::get_flash('username'),
			'error'			=> Session::get_flash('error')
		));
		
		Asset::css(array('bootstrap.min.css', 'style.css'), array(), 'login');
		
		return Response::forge($view);
	}
	
	
	/**
	 * Try to log the user.
	 * If success, we redirect him to the previous uri resquest catched by Controller class
	 * 
	 * @access public
	 * @return void
	 */
	public function action_check()
	{
    if (Input::post())
    {
    	$auth = Auth::instance();
      
      if ($auth->login())
      {
      	// Check for incomming url
      	if ($cook = Cookie::get('redirect'))
      	{
	      	Cookie::delete('redirect');
      	}

        Response::redirect($cook ?: Uri::base());
      }
      else
      {
      	$error = $auth->get_errors();
      	$error = str_replace('.', '', $error->error);
        
        Session::set_flash('error', Html::alert(__('error').':', __($error), 'danger'));
        Session::set_flash('username', Input::post('username'));
      }
    }
    Response::redirect('login', 'location', 403);
	}
	
	
	/**
	 * Logout the current user and redirect the the login form.
	 * 
	 * @access public
	 * @return void
	 */
	public function action_logout()
	{
		Auth::logout();
		Response::redirect('login', 'location', 403);
	}
	
}