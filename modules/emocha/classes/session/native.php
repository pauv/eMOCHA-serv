<?php defined('SYSPATH') or die('No direct script access.');

// FIX FOR SESSION CORRUPTION BUG
class Session_Native extends Kohana_Session_Native {

	protected function _read($id = NULL) { 
		// Set the cookie lifetime 
		session_set_cookie_params($this->_lifetime); 
		// Set the session cookie name 
		session_name($this->_name); 
		if ($id) { 
			// Set the session id 
			session_id($id); 
		} 
		try { 
			// Start the session 
			session_start(); 
			// Use the $_SESSION global for storing data 
			$this->_data =& $_SESSION; 
		} 
		catch (Exception $e) { 
			$this->destroy(); 
			$this->_data = array();
		} 
		
		return NULL; 
	}



}
