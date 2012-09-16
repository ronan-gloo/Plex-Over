<?php


/**
 * Login throught MyPlex Service.
 * MyPlex service should be activated in order to check the user and authenticate the user
 */
class Auth_Login_Local extends Auth\Auth_Login_Driver {
	
	protected $errors, $users;
	
	
	/**
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->users = Config::load('users', true);
	}
	
	/**
	 * Try to login and get Token from MyPlex
	 * 
	 * @access public
	 * @param string $username (default: '')
	 * @param string $password (default: '')
	 * @return void
	 */
	public function login($username = null, $password = null)
	{
		// forced or input data
		$username = $username ?: Input::post('username');
		$password = $password ?: Input::post('password');
				
		if ($this->users)
		{
			if (isset($this->users[$username]) and $this->users[$username] === $password)
			{
				return $this->validate_user(compact('username', 'password'));
			}
		}
		
		$this->errors['error'] = 'Please sign in';
		
		return false;
	}
	
	/**
	 * Validate user: check if user allowed here
	 *
	 * @return  bool
	 */
	public function validate_user($user = null)
	{		
		return Session::set('username', $user['username']);
	}
	
	/**
	 * Perform the actual login check
	 *
	 * @return  bool
	 */
	protected function perform_check()
	{
		if ($this->users)
		{
			$key = Session::get('username');
			
			return ! empty($this->users[$key]);
		}
		return false;
	}
	
	
	/**
	 * Destroy session and empty $this->user
	 */
	public function logout()
	{
		return Session::delete('username');
	}
	
	
	/**
	 * Returns login errors.
	 * 
	 * @access public
	 * @return void
	 */
	public function get_errors()
	{
		return (object)$this->errors;
	}
	
	/**
	 * Get User Identifier of the current logged in user
	 * in the form: array(driver_id, user_id)
	 *
	 * @return  array
	 */
	public function get_user_id()
	{
		return $this->user->username;
	}
	

	/**
	 * Get User Groups of the current logged in user
	 * in the form: array(array(driver_id, group_id), array(driver_id, group_id), etc)
	 *
	 * @return  array
	 */
	public function get_groups()
	{
		
	}

	/**
	 * Get emailaddress of the current logged in user
	 *
	 * @return  string
	 */
	public function get_email()
	{

	}

	/**
	 * Get screen name of the current logged in user
	 *
	 * @return  string
	 */
	public function get_screen_name()
	{
		return Session::get('username');
	}
	
}