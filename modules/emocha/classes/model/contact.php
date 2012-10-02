<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Referral Model
 *
 * @package    eMOCHA
 * @author     Pau Varela
 * @copyright  2012 Pau Varela - pau.varela@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Model_Contact extends ORM {

	protected $_has_many = array('phones' => array('through' => 'phone_contacts'));

/**
     * get_contact()
     * 
     * Get array of variables for api return
     * 
     * @return array
     */
    public function get_contact() {
      $contact = array();
      $contact['id'] = $this->id;
      $contact['name'] = $this->name;
      $contact['phone_number'] = $this->phone_number;

      return $contact;
		}
    
}
