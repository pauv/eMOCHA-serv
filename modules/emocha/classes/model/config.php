<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Config Model
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - george@ccghe.net
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */ 
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