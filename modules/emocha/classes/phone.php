<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Phone helper
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - george@ccghe.net
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Phone 
    {   	    
    
   		public static function get_id_val_array() {
			$arr = array(''=>'');
			$phones = ORM::factory('phone')->find_all();
			foreach($phones as $phone) {
				$arr[$phone->id] = $phone->imei;
			}
			return $arr;
		}
       
        public static function get_phone_list() {
        	return ORM::factory('phone')->find_all();
        }
        

		        
        public static function get_gps_phone_list() {
        	return ORM::factory('phone')
        			->where('gps', 'LIKE', '% %')
        			->find_all();
        }
        
        
        
        /*
         * Activate phone
         */
		public static function activate($imei, $auto_validate=FALSE) {
        	
        	// check valid code
			if ( ! Phone::is_imei_valid($imei) && ! Phone::is_cdma_valid($imei)) {
				echo $imei;
				return array(
						'msg'=>Kohana::message('phone', 'activate.phone_activation_bad_imei'),
						'phone_id'=>0
						);
			}

			// check if already in database
			$phone = ORM::factory('phone')->where('imei', '=', $imei)->find();
			if ($phone->loaded()) {
				return array(
						'msg'=>Kohana::message('phone', 'activate.phone_activation_exists'),
						'phone_id'=>$phone->id
						);
			}				
			
			
			// add to database
			$ip=$_SERVER['REMOTE_ADDR'];
			$phone = ORM::factory('phone');
			$phone->imei = $imei;
			$phone->imei_md5 = md5($imei);
			$phone->validated = 0;
			$phone->creation_ts = time();
			$phone->creation_ip = $ip;
			$phone->save();
			
			// versions requiring no manual validation
			if($auto_validate) {
				$phone->validated = 1;
				$phone->save();
				return array(
							'msg'=>Kohana::message('phone', 'activate.phone_auto_validate'),
							'phone_id'=>$phone->id
							);
			}
			// versions requiring manual validation
			else {
				$to = Kohana::config('emocha.admin_alerts_to');
				$from = Kohana::config('email.options.username');
				$subject = 'Phone activation requested';
				$message = "Sent from: ".Url::site();
				$message .= "\n\nPhone IMEI: ".$phone->imei;	
				$message .= "\n\nLogin to the backend and go to the 'admin' section to activate this phone.";
				Email::send($to, $from, $subject, $message);
				
				return array(
							'msg'=>Kohana::message('phone', 'activate.phone_activation_sent'),
							'phone_id'=>$phone->id
							);
			}

		}
		
		/*
		 * Check for IMEI type id validity
		 * (used in GSM phones)
		 */
		public static function is_imei_valid($imei) {
			if(!ctype_digit($imei)) {
				return false;
			}
			$len = strlen($imei);
			if($len != 15) {
				return false;			
			}
	
			// Set the string length and parity
			$parity=$len % 2;
			 
			// Loop through each digit and do the maths
			$total=0;
			for ($i=0; $i<$len; $i++) {
				$digit=$imei[$i];
				// Multiply alternate digits by two
				if ($i % 2 == $parity) {
				  $digit*=2;
				  // If the sum is two digits, add them together (in effect)
				  if ($digit > 9) {
					$digit-=9;
				  }
				}
				// Total up the digits
				$total+=$digit;
			}
			 
			// If the total mod 10 equals 0, the number is valid
			return ($total % 10 == 0) ? true : false;
		}
		
		
		/*
		 * Check for ESN or MEID type id validity
		 * (used in CDMA phones)
		 */
		public static function is_cdma_valid($id) {
			return ( preg_match('/^[0-9a-fA-F]{8}$/', $id) || // 8 digit hex (esn)
					preg_match('/^[0-9]{11}$/', $id) || // 11 digit decimal (esn)
					preg_match('/^[0-9a-fA-F]{14}$/', $id) || // 14 digit hex (meid)
					preg_match('/^[0-9]{18}$/', $id) ); // 18 digit decimal (meid)
		}
		
		
        /*
         * Get phone based on usr
         * return mixed - phone object or false
         */
        public static function get_by_user($usr) {
        
        	if(! $usr) return FALSE;
        	
			$phone = ORM::factory('phone')
								->where('imei_md5', '=', $usr)
								->and_where('validated', '=', 1)
								->find();
			
			return $phone->loaded() ? $phone: FALSE;
		}
		
        /*
         * Get phone based on usr and password
         * return mixed - phone object or false
         */
        public static function get_by_user_password($usr, $pwd = '') {
        
        	if(! $usr || ! $pwd) return FALSE;
        	
			$phone = ORM::factory('phone')
								->where('imei_md5', '=', $usr)
								->and_where('pwd', '=', DB::expr("PASSWORD('$pwd')"))
								->and_where('validated', '=', 1)
								->find();
			
			return $phone->loaded() ? $phone: FALSE;
		}		

		
	}
