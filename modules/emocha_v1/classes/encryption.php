<?php defined('SYSPATH') or die('No direct script access.');

class Encryption {


	
	public static function get_key() {
		return Kohana::config('encryption.key');
	}

	
}