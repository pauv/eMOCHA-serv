<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Alarm Action Model
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */ 
class Model_Alarm_Action extends ORM {
	
	protected $_belongs_to = array(
								'alarm' => array(),
								'user' => array()
							);
	/*
	 * execute()
	 * check condition
	 * @return string/bool (status message or false)
	 */								
									
	public function execute($msg='') {
	
		// currently just hard coding
		// the action types
		switch ($this->type) {
			
			case 'email':
				$subject = "eMocha Alarm: ".$this->alarm->name;
				$message = "Alarm triggered:\n\n"
							.$msg;
				$to = $this->user->email;
				$from = Kohana::config('email.options.username');
				if (Email::send($to, $from, $subject, $message)) {
					return "Email sent to ".$this->user->email;
				}
				return FALSE;
				break;
				
		}
		
		return false;
	}

}