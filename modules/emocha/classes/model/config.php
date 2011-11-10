<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Config extends ORM {

	/*
 	 * Validation for editing config details
 	 */
	public function validate(& $array, $mode) 
	{
		// Initialise the validation library and use some rules
		$array = Validate::factory($array)
						->rules('label', array(
												'not_empty'=>NULL
												))
						->rules('content', array(
												'not_empty'=>NULL
												))
						->filter('description', 'trim');
 
		return $array;
	}

}