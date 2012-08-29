<?php


/**
 * Login throught MyPlex Service.
 * MyPlex service should be activated in order to check the user and authenticate the user
 */
class Auth_Login_MyPlex extends Auth\Auth_Login_Driver {
	
	protected static
		
		/**
		 * MyPlex service base url
		 * 
		 * @var mixed
		 * @access private
		 */
		$_baseurl	= 'https://my.plexapp.com/',
		
		
		/**
		 * Format returned by the MyPlex API
		 * 
		 * (default value: 'json')
		 * 
		 * @var string
		 * @access private
		 */
		$_format	= 'json';
	
	protected
		
		/**
		 * Store user if validated
		 * 
		 * @var mixed
		 * @access public
		 */
		$user		= null,
		
		
		/**
		 * Store login errors
		 * 
		 * (default value: null)
		 * 
		 * @var mixed
		 * @access public
		 */
		$errors = null;

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

		// Instanciate our request to myplex
		$request = $this->request('users/sign_in');
		$request->http_login($username, $password);
		
		// Try to get the request
		try {
			$output = json_decode($request->execute()->response());
			return $this->validate_user($output->user);
		}
		catch (RequestStatusException $e) {
			$this->errors = json_decode($e->getMessage());
		}
		catch (RequestException $e) {
			$this->errors = json_decode($e->getMessage());
		}
		return false;
	}
	
	/**
	 * Validate user: check if user allowed here
	 *
	 * @return  bool
	 */
	public function validate_user($user = null)
	{
		if ($token = $this->get_token() and $user->authentication_token == $token)
		{
			Session::set('auth_token', $token);
			Session::set('username', $user->username);
			
			return true;
		}
		else
		{
			! isset($this->errors['error']) and $this->errors['error'] = 'invalid_token';
		}
		
		$this->user = false;
		
		return $this->user;
	}
	
	/**
	 * Perform the actual login check
	 *
	 * @return  bool
	 */
	protected function perform_check()
	{
		return ($token = $this->get_token() and $token == Session::get('auth_token'));
	}
	
	
	/**
	 * Get token from local server and returns it if succeded
	 * 
	 * @access protected
	 * @return void
	 */
	public function get_token()
	{
		try {
			$cont = Request::forge(Plex::base_url().'myplex/account', 'curl')->execute()->response();
			$data = simplexml_load_string($cont->body);
			
			if (! $this->user)
			{
				$this->user = $this->get_user_attrs($data->attributes());
			}
			return $this->user->auth_token;
		}
		catch (RequestStatusException $e) {
			$this->errors['error'] = $e->getMessage();
		}
		catch (RequestException $e) {
			$this->errors['error'] = $e->getMessage();
		}
		return false;
	}
	
	/**
	 * Retrieve token from PMS.
	 * 
	 * @access public
	 * @return void
	 */
	public function local_token()
	{
		$cont = Request::forge(Plex::base_url().'myplex/account', 'curl')->execute()->response();
		$data = simplexml_load_string($cont->body);
		$data = $this->get_user_attrs($data->attributes());
		
		return $data->auth_token;
	}
	
	/**
	 * Attibutes from local server.
	 * 
	 * @access protected
	 * @param mixed $attrs
	 * @return void
	 */
	protected function get_user_attrs($attrs)
	{
		$user = new stdClass;
		$user->auth_token = (string)$attrs->authToken;
		$user->email = (string)$attrs->username;
		
		return $user;
	}
	
	/**
	 * Retunrs a request instance.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $path (default: null)
	 * @return void
	 */
	protected function request($path = null, array $options = array())
	{
		$options['driver'] = 'curl';
		$req_url = self::$_baseurl.$path.'.'.self::$_format;
		$request = Request::forge($req_url, $options);
		
		// set options
		return $request->set_options($this->set_options());
	}
	
	
	/**
	 * Set default Curl options and headers
	 * 
	 * @access protected
	 * @static
	 * @return void
	 */
	protected function set_options()
	{
		return array(
			'post'						=> true,
			'postfields'			=> true,
			'returntransfer' 	=> true,
			'httpheader' 			=> array(
				'X-Plex-Client-Identifier: '.Config::get('main.identifier'),
				'X-Plex-Platform: '.APPNAME,
				'X-Plex-Platform-Version: '.APPVERS,
				//'X-Plex-Provides: ',
				//'X-Plex-Product: ',
				//'X-Plex-Version: ',
				//'X-Plex-Device: '
			)
		);
	}
	
	/**
	 * Destroy session and empty $this->user
	 */
	public function logout()
	{
		Session::delete('username');
		Session::delete('auth_token');
		
		return $this->user = false;
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
		return $this->user->email;
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