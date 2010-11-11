<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Phone extends ORM {

	protected $_table_name = 'phone';
	protected $_sorting = array('last_connect_ts'=>'DESC');
	
	
	protected $_has_many = array (
								'form_datas'=>array('foreign_key'=>'uploader_phone_id')
							);
	
	
	
	// activate phone via admin
	public function edit($validated, $password, $comments) {
        	
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
		$gps = preg_replace('/[^0-9.,:-]/', '', $gps);
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
}