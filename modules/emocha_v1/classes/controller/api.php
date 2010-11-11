<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Controller {

	protected $phone;
	
	public function before()
	{
		parent::before();
		
		switch($this->request->action) {
			case 'activate_phone':
				// no user check required
			break;
			
			default:
				// check user and update gps / connect time info
				$this->phone = Phone::get_by_user_password(Arr::get($_POST, 'usr'), Arr::get($_POST, 'pwd')); 
				if ( ! $this->phone) {
					$json = View::factory('json/display', Json::response('ERR', 'unknown user'))->render();
					echo $json;
					//echo View::factory('profiler/stats');
					exit;
				} else {
					$this->phone->set_gps(Arr::get($_POST, 'gps'));					
				}
		}	

	}
	
	
	public function action_check_user() {
		$json = View::factory('json/display', Json::response('OK', 'known user'))->render();
		$this->request->response = $json;
	}
	
	public function action_activate_phone() {
    	$imei = preg_replace('/\W/', '', Arr::get($_POST, 'imei', ''));    	
    	$result = Phone::activate($imei);
    	$msg = $result['msg'];
    	$phone_id = $result['phone_id'];
    	if(! $phone_id) {
    		$json = View::factory('json/display', Json::response('ERR', $msg, array('phone_id'=>0)))->render();
    	}
    	else {
			$json = View::factory('json/display', Json::response('OK', $msg, array('phone_id'=>$phone_id)))->render();
		}
		$this->request->response = $json;
		
    }   
    
    
    /*
    TODO DEPRECATE
    */
    public function action_get_sdcard_file_list() {
 		
	  	$last_server_update = Sdcard::get_last_server_update();
	  	$responseA['last_server_upd'] = $last_server_update;
	
		if ($last_server_update != Arr::get($_POST, 'last_server_upd')) {
			$responseA['files'] = Sdcard::get_file_list_from_db();			
		} 

		// we return the last form-data upload timestamp
		// so the phone knows he must send us all form-data
		// newer than that timestamp
		$ts = $this->phone->get_last_upload_ts();
		$responseA['last_form_data_upload'] = $ts;
		
		$json = View::factory('json/display', Json::response('OK', 'get_sdcard_file_list', $responseA))->render();
		$this->request->response = $json;
    }
    
    
    public function action_get_server_updated_times() {
    	$response = Api::get_server_updated_times();
    	$json = View::factory('json/display', Json::response('OK', 'get_server_updated_times', $response))->render();
    	$this->request->response = $json;
    }
    
    public function action_get_app_config() {
    	$config = ORM::factory('config')->where('label', '=', 'application')->find();
    	if($config->loaded()) {
    		// content is already in json in db, so decode
    		// and then re-encode in view
    		$response = array('config'=>json_decode($config->content));
    		$json = View::factory('json/display', Json::response('OK', 'get_app_config', $response))->render();
    		$this->request->response = $json;
    	}
    }
    
     public function action_get_form_config() {
    	$forms = ORM::factory('form')->where('archived','=',0)->find_all();
    	$response = array();
    	foreach($forms as $form) {
    		$response[] = $form->get_config();
    		
    	}
    	$json = Json::response_array('OK', 'get_form_config', $response, 'config', 'forms');
    	$this->request->response = $json;
    }
    
    
    public function action_get_form_templates() {
    
    	$last_server_update = Sdcard::get_last_server_update();
	  	$responseA['last_server_upd'] = $last_server_update;
	
		if ($last_server_update != Arr::get($_POST, 'last_server_upd')) {
			$forms = ORM::factory('form')->where('archived','=',0)->find_all();
			$files_arr = array();
			foreach ($forms as $form) {
				if ($form->file->loaded()) {
					$files_arr[] = $this->file_as_array($form->file);
				}
			}
			$responseA['files'] = $files_arr;			
		} 
    	$json = View::factory('json/display', Json::response('OK', 'get_form_templates', $responseA))->render();
		$this->request->response = $json;
		
    }
    
     public function action_get_media_files() {
    
    	$last_server_update = Sdcard::get_last_server_update();
	  	$responseA['last_server_upd'] = $last_server_update;
	
		if ($last_server_update != Arr::get($_POST, 'last_server_upd')) {
			$medias = ORM::factory('media')->find_all();
			$files_arr = array();
			foreach ($medias as $media) {
				if ($media->file->loaded()) {
					$files_arr[] = $this->file_as_array($media->file);
				}
			}
			$responseA['files'] = $files_arr;			
		} 
    	$json = View::factory('json/display', Json::response('OK', 'get_media_files', $responseA))->render();
		$this->request->response = $json;
		
    }
    
    
    private function file_as_array ($file) {
    	$arr = Array(
					"path"		=> $file->path, 
					"ts"		=> $file->ts, 
					"size"		=> $file->size, 
					"md5"		=> $file->md5
		  			);
		return $arr;
    }
    
    
    
	/*
	 * Upload form data from phone
	 */
    function action_upload_form_data() {
    
		if(! Arr::get($_POST,"xml_content")) {
			$json = View::factory('json/display', Json::response('ERR', 'xml_content is empty'))->render();
		}
		else {
		
			$creator_phone = $this->phone; 
			
			/*
			Try to load existing form data record
			*/
			$form_data = Model_Form_Data::get_by_key_data(
								Arr::get($_POST,"household_code"),
								Arr::get($_POST,"patient_code"),
								Arr::get($_POST,"form_id")
								);
			
			
			/*
			Update form data values
			*/
			$form_data->household_code = Arr::get($_POST,"household_code");
			$form_data->patient_code = Arr::get($_POST,"patient_code");
			$form_data->creator_phone_id = $creator_phone->id;
			$form_data->form_id = Arr::get($_POST,"form_id");
			$form_data->uploader_phone_id = $this->phone->id;
			$form_data->xml_content = Arr::get($_POST,"xml_content");
			$form_data->file_path = Arr::get($_POST,"file_path");
			$form_data->last_modified = Arr::get($_POST,"last_modified");
			$form_data->display_label = Arr::get($_POST,"display_label");
			
			
			// check the form type
			$form = ORM::factory('form', $form_data->form_id);
			
			/*
			Save household and patient data if necessary
			TODO: these form id numbers probably should not be hardwired
			as the only way to recognise the core forms
			*/
			if($form->code=='household_core') {
				$household = Model_Household::save_from_form_data($form_data);
			}
			if($form->code=='patient_core') {
				$patient = Model_Patient::save_from_form_data($form_data);
				/*
				Deal with patient image upload
				TODO: develop a system for dealing with multiple files from any form
				*/
				if(isset($_FILES['image'])){
					$validation = Validate::factory($_FILES)
						->rules('image', array(
												'upload::valid'=>NULL, 
												'upload::type'=>array(array('jpg')), 
												'upload::size'=>array('1M')
												));
					if ($validation->check()){
						$patient->save_profile_image($_FILES['image']);
					}
				}
			}
		

			/*
			Insert or update the form data as the case may be
			*/
			if ($form_data->save()) {
				
				$json = View::factory('json/display', Json::response('OK', 'data_uploaded'))->render();
			} 
			else {
				$json = View::factory('json/display', Json::response('ERR', 'affected=0'))->render();
			}

		}
		//echo View::factory('profiler/stats');
		$this->request->response = $json;
		
	}
	
	
	/*
	 * Upload file from phone
	 */
	 /*
    function action_upload_file() {

		if(! $path0 = Arr::get($_POST, "path0")) {
			$json = View::factory('json/display', Json::response('ERR', "path0 is empty"))->render();
		}
		
		else {
			$path = pathinfo(trim($path0, '/'));
			$directory = 'sdcard/'.$this->phone->id.'/'.$path['dirname'];
			if (!is_dir($directory)) {
				if (!mkdir($directory, 0755, true)) {
					$json = View::factory('json/display', Json::response('ERR', "Can not create user directory $directory"))->render();
					$this->request->response = $json;
					return;    
				}
			}
		
			$validation = Validate::factory($_FILES)
						->rules('file0', array(
												'upload::not_empty'=>NULL,
												'upload::valid'=>NULL, 
												'upload::type'=>array(array('txt','xml','png','jpg')), 
												'upload::size'=>array('1M')
												));
			if ( ! $validation->check()){
				$json = View::factory('json/display', Json::response('ERR', "Invalid or non-existent file"))->render();
			} 
			else {
				// save the file
				if(! upload::save($_FILES['file0'], $path['filename'], $directory) ) {
					$json = View::factory('json/display', Json::response('ERR', "file upload failed"))->render();
				}
				
				else {
					$json = View::factory('json/display', Json::response('OK', "uploaded"))->render();
				}
			
			}
		}
		
		$this->request->response = $json;
	}
	*/
	
	
	
	
	
	

}