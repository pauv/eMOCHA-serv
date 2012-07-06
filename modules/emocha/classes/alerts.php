<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Alerts helper (Google Cloud Messaging)
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Alerts
    { 
    

		/**
		 *  send_message()
		 *
		 * Send Alerts message (currently using GCM)
		 *
		 * @param string
		 * @param string
		 * @param object
		 * @param string
		 *
		 * @return string or bool
		 */
		public static function send_message($auth_key, $alert, $phone, $collapse_key) {
			$ch = curl_init();
		
			$headers = array("Content-Type:application/json","Authorization:key=".$auth_key);
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
			
			$data = array('registration_ids' => array($phone->alerts_id),
			'collapse_key' => $collapse_key,
			'delay_while_idle' => false,
			'data.alert_id' => $alert->id,
			'data.message_type' => $alert->message_type,
			'data.form_code' => $alert->form_code,
			'data.message' => urlencode($alert->message),
			'data.pn_sent' => date('YmdHis'));
			
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			
			$response = curl_exec($ch);
			$info = curl_getinfo ($ch);
			
			if(curl_errno($ch)) {
				Alerts::log_error('send_message', curl_error($ch), '', '', serialize($data), $phone->id);
				return FALSE;
			}
			elseif($info['http_code']!=200 || stristr($response, 'error')) {
				Alerts::log_error('send_message', '', $info['http_code'], $response, serialize($data), $phone->id);
				return FALSE;
			}
			else {
				return $response;
			}
			
		} 
		
		
		 /**
		 *  log_error()
		 *
		 * Log Alerts sending error
		 *
		 * @param string
		 * @param string
		 * @param string
		 * @param string
		 * @param string
		 * @param int
		 */
		 public static function log_error($type, $curl_error, $http_code, $response, $data, $phone_id=0) {
		 	$err = ORM::factory('alerts_error');
		 	$err->type = $type;
		 	$err->curl_error = $curl_error;
		 	$err->http_code = $http_code;
		 	$err->response = $response;
		 	$err->data = $data;
		 	$err->phone_id = $phone_id;
		 	$err->save();
		 }

		
}