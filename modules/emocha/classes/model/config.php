<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Config Model
 *
 * @package    eMOCHA
 * @author     George Graham
 * @author     Pau Varela
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @copyright  2012 Pau Varela - pau.varela@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */ 
class Model_Config extends ORM {

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
						->rules('label', array(
												'not_empty'=>NULL
												))
						->rules('content', array(
												'not_empty'=>NULL
												))
						->filter('description', 'trim')
						->filter('label', 'trim')
						->filter('content', 'trim');
 
		return $array;
	}
	
	public static function validate_time_zone($timezone)
	{
		return (bool) in_array($timezone, DateTimeZone::listIdentifiers());
	}
}
