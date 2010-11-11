<?php


class Model_User extends Model_Auth_User {
 
 
 
 	/*
 	 * Validation for creating new user
 	 */
	public function validate_create(& $array) 
	{
		// Initialise the validation library and use some rules
		// mostly already defined in parent
		$array = Validate::factory($array)
						->rules('password', $this->_rules['password'])
						->rules('username', $this->_rules['username'])
						->rules('email', $this->_rules['email'])
						->rules('password_confirm', $this->_rules['password_confirm'])
						->rules('first_name', array(
												'not_empty'=>NULL
												))
						->rules('last_name', array(
												'not_empty'=>NULL
												))
						->filter('username', 'trim')
						->filter('email', 'trim')
						->filter('password', 'trim')
						->filter('password_confirm', 'trim')
						->filter('first_name', 'trim')
						->filter('last_name', 'trim');
 
		// Executes username callbacks defined in parent		
		foreach($this->_callbacks['username'] as $callback){
			$array->callback('username', array($this, $callback));
		}
 
        // Executes email callbacks defined in parent	
		foreach($this->_callbacks['email'] as $callback){
			$array->callback('email', array($this, $callback));
		}		
 
		return $array;
	}
	
	/*
 	 * Validation for resetting password
 	 */
	public function validate_reset_password(& $array) 
	{
		// Initialise the validation library and use some rules
		// mostly already defined in parent
		$array = Validate::factory($array)
						->rules('password', $this->_rules['password'])
						->rules('password_confirm', $this->_rules['password_confirm'])
						->filter('password', 'trim')
						->filter('password_confirm', 'trim');
 
		return $array;
	}
	
	/*
 	 * Validation for editing user account details
 	 */
	public function validate_edit(& $array) 
	{
		// Initialise the validation library and use some rules
		// mostly already defined in parent
		$array = Validate::factory($array)
						->rules('email', $this->_rules['email'])
						->rules('first_name', array(
												'not_empty'=>NULL
												))
						->rules('last_name', array(
												'not_empty'=>NULL
												))

						->filter('email', 'trim')
						->filter('first_name', 'trim')
						->filter('last_name', 'trim')
						->callback('email', array($this, 'email_change_available'));	
 
		return $array;
	}
	
	/**
	 * Email change validation callback
	 * @param    Validate  $array   validate object
	 * @param    string    $field   field name
	 * @param    array     $errors  current validation errors
	 * @return   array
	 */
	public function email_change_available(Validate $array, $field)
	{
		// check the database for existing records
		   $email_exists = (bool) ORM::factory('user')
		   						->where('email', '=', $array[$field])
		   						->and_where('id', '!=', $this->id)
		   						->count_all();
		 
		   if ($email_exists)
		   {
			   // add error to validation object
			   $array->error($field, 'email_available');
		   }
	}
	
	
	
	/**
	 * Validates login information from an array, and optionally redirects
	 * after a successful login.
	 *
	 * @param  array    values to check
	 * @param  string   URI or URL to redirect to
	 * @return boolean
	 */
	public function login(array & $array, $redirect = FALSE) {
	
		// call parent login function
		// without redirect
		$status = parent::login($array, FALSE);
		
		// if login successful, do extra checks
		if($status && (!$this->activated || !$this->confirmed)) {
			// User not yet activated and confirmed
			// Sign out the user again
			Auth::instance()->logout();
			$status = FALSE;
			$array->error('username', 'unconfirmed');
		}
		
		if ($status && is_string($redirect)) {
			// Redirect after a successful login
			Request::instance()->redirect($redirect);
		}
		
		// set app specific base url session var
		// to avoid logins leaking between apps
		if ($status) {
			Session::instance()->set('base_url', Kohana::$base_url);
		}
		
		return $status;
		
	}
	
	
	
	
	/*
	 * Send and record activation code for sign-up or password change
	 * @return bool	success or failure
	 */
	public function send_verification_email($type) {
	
		$verification = ORM::factory('user_verification');
		$verification->type = $type;
		$verification->user_id = $this->id;
		$verification->email = $this->email;
		$verification->generate_code();
		
		if ($verification->save() && $verification->send_email()) {
			return TRUE;
		}
		return FALSE;
		
	}
	
	 /*
	 * set user account as verified
	 */
	public function activate() {

		$this->activated = 1;
		$this->save();
		
	}
	
	/*
	 * set user account as verified
	 */
	public function confirm() {

		$this->confirmed = 1;
		$this->save();
		
		$to = $this->email;
		$from = 'server@ccghe.net';
		$subject = "eMocha Sign-up confirmed";
		$message = "Thanks for signing up\n"
					."Your account has now been confirmed.\n\n"
					."Go here to login:\n"
					.Url::site();
		email::send($to, $from, $subject, $message);
		
	}
	
		
	/*
	* checks for an in-process email change
	* @return string email address
	*/
	public function awaiting_email_change () {
	
		$verification = ORM::factory('user_verification')
							->where( array('user_id'=>$this->id, 'type'=>'email_change'))
							->orderby('date_created', 'DESC')
							->find();
							
		if ($verification->loaded && $verification->verified==0) {
    		return $verification->email;
    	}
    	
    	return FALSE;	
	}
	
	
	
	public static function get_unconfirmed_users() {
	
		$users = ORM::factory('user')
							->where('confirmed', '=', 0)
							->and_where('activated', '=', 1)
							->find_all();
		return $users;
	}
	
	
	/*
 	 * return list of user emails as id=>val
 	 * (useful for dropdowns)
 	 */
	
	public static function get_id_email_array() {
		$arr = array();
		$users = ORM::factory('user')->find_all();
		foreach($users as $user) {
			$arr[$user->id] = $user->email;
		}
		return $arr;
	}
	
}