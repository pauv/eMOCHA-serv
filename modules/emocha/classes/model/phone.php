<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Phone extends ORM {

	protected $_table_name = 'phone';
	protected $_sorting = array('last_connect_ts'=>'DESC');
	
	
	protected $_has_many = array (
								'form_datas'=>array('foreign_key'=>'uploader_phone_id'),
								'phone_locations'=>array()
							);
	
	
	
	// activate phone via admin
	public function edit($imei, $validated, $password, $comments) {
        
        if(!$this->loaded()) {
        	$this->creation_ts = time();
        }
        $this->imei = $imei;
        $this->imei_md5 = md5($imei);
        $this->validated = $validated;
        $this->comments = $comments;
        $this->save();
        
        // update the password
        if($password) {
			$sql = "UPDATE ".$this->_table_name."
					SET pwd = PASSWORD('".$password."')
					WHERE id=".$this->id;
			$result = DB::query(Database::UPDATE, $sql)->execute();
		}

	}
	
	
	
	public function set_gps($gps = '') {
		$gps = trim($gps);
		//$gps = preg_replace('/[^0-9.,:-]/', '', $gps);
		$this->gps = $gps;
		$this->last_connect_ts = time();
		$this->save();					        	
	}
	
	
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
   	
   	
   	public function save_locations($file_path) {
		$handle = @fopen($file_path, "r");
		if (! $handle) {
			return 0;
		}
		
		else {
			$rows=0;
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
			return $rows;
		}
	}
	
	public function log_alert($message_type, $form_code='', $response='') {
		if($message_type=='form_reminder') {
			$alert = ORM::factory('phone_alert');
			$alert->phone_id = $this->id;
			$alert->message_type = $message_type;
			$alert->form_code = $form_code;
			$alert->response = $response;
			return $alert->save();
		}
	}
}