<?php defined('SYSPATH') or die('No direct script access.');


// modified connect() method to set time zone according to config

class Database_MySQL extends Kohana_Database_MySQL {


	public function connect()
	{
		parent::connect();
		
		$this->set_timezone();

	}
	
	
	
	public function set_timezone()
	{
		// Make sure the database is connected
		$this->_connection or $this->connect();
		
		// Take the php timezone and send it to mysql
		$status = (bool) mysql_query("SET time_zone = '".date('P')."'", $this->_connection);

		if ($status === FALSE)
		{
			throw new Database_Exception(':error',
				array(':error' => mysql_error($this->_connection)),
				mysql_errno($this->_connection));
		}
	}
	
	

}
