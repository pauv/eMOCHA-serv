<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Config Model
 *
 * @package    eMOCHA
 * @author     George Graham
 * @author     Pau Varela
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */ 
class Model_Stats_Config extends ORM {

	/**
	 * validate()
	 * 
	 * Validate edit details
	 *
	 * @param array
	 * @param string
	 * @return array
	 */
	public function validate(& $array, $mode) 
	{
		// Initialise the validation library and use some rules
		$array = Validate::factory($array)
						->rules('form_id', array(
												'not_empty'=>NULL
												))
						->rules('node', array(
												'not_empty'=>NULL
												))
						->rules('title', array(
												'not_empty'=>NULL
												))
						->filter('description', 'trim');
 
		return $array;
	}
	
}
