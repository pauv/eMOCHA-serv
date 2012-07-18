<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Phone Model
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Model_Phone extends ORM {

	protected $_table_name = 'phone';
	protected $_sorting = array('last_connect_ts'=>'DESC');
	
	
	protected $_has_many = array (
								'form_datas'=>array('foreign_key'=>'uploader_phone_id'),
								'phone_locations'=>array()
							);
	
	
	
	/**
	 * edit()
	 *
	 * Edit phone details
	 * 
	 * @param string
	 * @param int
	 * @param string
	 * @param string
	 * @param int
	 */
	public function edit($imei, $validated, $password, $comments, $enable_alerts) {
        
        if(!$this->loaded()) {
        	$this->creation_ts = time();
        }
        $this->imei = $imei;
        $this->imei_md5 = md5($imei);
        $this->validated = $validated;
        $this->comments = $comments;
        $this->enable_alerts = $enable_alerts;
        $this->save();
        
        // update the password
        if($password) {
			$sql = "UPDATE ".$this->_table_name."
					SET pwd = PASSWORD('".$password."')
					WHERE id=".$this->id;
			$result = DB::query(Database::UPDATE, $sql)->execute();
		}

	}
	
	
	/**
	 * set_gps()
	 *
	 * Update gps and last connect value
	 * 
	 * @param string
	 */
	public function set_gps($gps = '') {
		$gps = trim($gps);
		$this->gps = $gps;
		$this->last_connect_ts = time();
		$this->save();					        	
	}
	
	/**
	 * get_last_upload_ts()
	 *
	 * @return int
	 */
    public function get_last_upload_ts() {
   		$result = DB::select('last_modified')
   						->from('uploaded_data')
   						->where('uploader_phone_id', '=', $this->id)
   						->order_by('last_modified', 'desc')
   						->execute();
   		if($result->count()) {
   			$row = $result->current();
   			return $row['last_modified'];
   		}
   		else {
   			return 0;
   		}

   	}
   	
   	/**
	 * save_locations()
	 *
	 * Save uploaded gps data 
	 *
	 * @param string
	 * @return int
	 */
   	public function save_locations($file_path) {
		$handle = @fopen($file_path, "r");
		if (! $handle) {
			return 0;
		}
		
		else {
			$rows=0;
			
			try {
				DB::query(NULL, 'START TRANSACTION')->execute();
				while (($buffer = fgets($handle, 4096)) !== false) {
					$parts = explode(',', $buffer);
					if(count($parts)==5) {
						$loc = ORM::factory('phone_location');
						$loc->ts = trim($parts[0]);
						$loc->altitude = trim($parts[2]);
						$loc->speed = trim($parts[3]);
						$loc->bearing = trim($parts[4]);
						$gps = trim($parts[1]);
						$gps_parts = explode(' ', $gps);
						if(count($gps_parts)==2) {
							$loc->gps = $gps;
							$loc->gps_lat = trim($gps_parts[0]);
							$loc->gps_long = trim($gps_parts[1]);
							$loc->phone_id = $this->id;
							$loc->save();
							$rows++;
						}
					}
				}
				fclose($handle);
				DB::query(NULL, 'COMMIT')->execute();
			}
			catch (Exception $e) {
				DB::query(NULL, 'ROLLBACK')->execute();
				$rows=0;
			}

			return $rows;
		}
	}
	
	
	
	/**
	 * send_alert()
	 *
	 * Send c2dm alert to phone
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return bool
	 */
	public function send_alert($auth_key, $collapse_key, $message_type, $form_code='', $message='') {
		
		// create alert record
		$alert = ORM::factory('phone_alert');
		$alert->phone_id = $this->id;
		$alert->message_type = $message_type;
		$alert->form_code = $form_code;
		$alert->message = $message;
		
		if($alert->save()) {
			// try to send
			if($response = Alerts::send_message($auth_key, $alert, $this, $collapse_key)) {
				$alert->response = $response;
				$alert->sent = 1;
				return $alert->save();
			}
		}
		return FALSE;

	}
	
	
	/**
	 * log_alert()
	 *
	 * Log Gcm alert
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return bool
	 */
	public function log_alert($message_type, $form_code='', $message='', $response='') {
		$alert = ORM::factory('phone_alert');
		$alert->phone_id = $this->id;
		$alert->message_type = $message_type;
		$alert->form_code = $form_code;
		$alert->response = $response;
		return $alert->save();
	}
}