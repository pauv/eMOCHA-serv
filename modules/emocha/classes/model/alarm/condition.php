<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Alarm Condition Model
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */ 
class Model_Alarm_Condition extends ORM {
	
	protected $_belongs_to = array('alarm' => array()
									);
	
	public $value_found;
	
	/**
	 * check()
	 * 
	 * Check condition met or not
	 *
	 * @return bool
	 */
	public function check() {
	
		// currently just hard coding
		// the condition labels
		switch ($this->label) {
			
			case 'patient_number_limit':
				$this->value_found = ORM::factory('patient')->count_all();
				return ($this->value_found > $this->value);
				break;
				
		}
		
		return false;
	}

}