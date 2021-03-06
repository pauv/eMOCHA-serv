<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Api Controller
 *
 * @package    eMOCHA
 * @author     George Graham
 * @author     Pau Varela
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @copyright  2012 Pau Varela - pau.varela@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Emocha_Controller_Api extends Controller {

	// connecting phone
	protected $phone;
	
	
	/**
	 * before()
	 *
	 * Runs before any other action when the controller is called
	 * Authenticates phone
	 */
	public function before()
	{
		parent::before();
		
		switch($this->request->action) {
			case 'activate_phone':
				// no user check required
			break;
			
			default:
				// check user and update gps / connect time info
				$auth_type = ORM::factory('config')->where('label','=',Kohana::config('values.authentication'))->find();
				if ($auth_type->loaded() AND $auth_type->content) 
				{
					if ($auth_type->content == Kohana::config('values.usr_only'))
					{
						$this->phone = Phone::get_by_user(Arr::get($_POST, 'usr'));
					} 
					elseif ($auth_type->content == Kohana::config('values.usr_password'))
					{
						$this->phone = Phone::get_by_user_password(Arr::get($_POST, 'usr'), Arr::get($_POST, 'pwd'));
					} 
					elseif ($auth_type->content == Kohana::config('values.usr_password_session'))
					{
						$this->phone = Phone::get_by_user_password_session(Arr::get($_POST, 'usr'), Arr::get($_POST, 'pwd'), Arr::get($_POST, 'session_pwd'));
					} 
					else 
					{
						$json = View::factory('json/display', Json::response('ERR', Kohana::config('errors.wrong_auth_type')))->render();
						echo $json;
						exit;
					}
				} else
				{
					$json = View::factory('json/display', Json::response('ERR', Kohana::config('errors.config_missing')))->render();
					echo $json;
					exit;
				}


				if ( ! $this->phone) {
					$json = View::factory('json/display', Json::response('ERR', Kohana::config('errors.unknown_user')))->render();
					echo $json;
					//echo View::factory('profiler/stats');
					exit;
				} else {
					if($gps = Arr::get($_POST, 'gps')) {
						$this->phone->set_gps($gps);	
					}
				}
		}	

	}
	
	 

	/**
	 * action_check_user()
	 *
	 * Check if user passed authentication
	 *
	 */
	public function action_check_user() {
		$json = View::factory('json/display', Json::response('OK', 'known user'))->render();
		$this->request->response = $json;
	}


	/**
	 * action_get_config_by_key()
	 *
	 * Get single config value by key
	 *
	 */
	public function action_get_config_by_key() {
	 	$label = Arr::get($_POST, 'key', ''); 
    	$config = ORM::factory('config')->where('label', '=', $label)->find();
    	if($config->loaded()) {
    		$json = View::factory('json/display', Json::response('OK', 'get_config_by_key', array('keys'=>array($config->label=>$config->content))))->render();
    		$this->request->response = $json;
    	}
    	else {
    		$json = View::factory('json/display', Json::response('ERR', 'no config found'))->render();
			$this->request->response = $json;
    	}
    }
    
    /**
	 * action_get_config_by_keys()
	 *
	 * Get multiple config values by key
	 *
	 */
    public function action_get_config_by_keys() {
    	$configs = array();
	 	if(! $keys = Arr::get($_POST, 'keys', '')) {
	 		// no keys submitted, get all keys
    		//$objs = ORM::factory('config')->where('label','!=','application')->find_all();
    		$objs = ORM::factory('config')->find_all();
    		foreach($objs as $obj){
    			$configs[$obj->label] = $obj->content;
    		}
    	}
    	else {
    		$labels = explode(',', $keys);
    		foreach($labels as $label) {
    			$config = ORM::factory('config')->where('label', '=', $label)->find();
    			if($config->loaded()) {
    				$configs[$config->label] = $config->content;
    			}
    		}
    	}
    	if(sizeof($configs)) {
    		$json = View::factory('json/display', Json::response('OK', 'get_config_by_keys', array('keys'=>$configs)))->render();
    		$this->request->response = $json;
    	}
    	else {
    		$json = View::factory('json/display', Json::response('ERR', 'no configs found'))->render();
			$this->request->response = $json;
    	}
    }
	
	
	/**
	 * action_activate_phone()
	 *
	 * Activate new phone
	 *
	 */
	public function action_activate_phone() {
    	$imei = preg_replace('/\W/', '', Arr::get($_POST, 'imei', ''));    	
    	$result = Phone::activate($imei);
    	$msg = $result['msg'];
    	$phone_id = $result['phone_id'];
    	if(! $phone_id) {
    		$json = View::factory('json/display', Json::response('ERR', $msg, array('phone_id'=>0)))->render();
    	}
    	else {
    		if(isset($result['session_pwd'])) {
    			$json = View::factory('json/display', Json::response('OK', $msg, array('phone_id'=>$phone_id, 'session_pwd'=>$result['session_pwd'])))->render();
    		}
    		else {
    			$json = View::factory('json/display', Json::response('OK', $msg, array('phone_id'=>$phone_id)))->render();
    		}
		}
		$this->request->response = $json;
		
    }
    
    
    /**
	 * action_get_server_update_times()
	 *
	 * Get last update values for server configs and media
	 *
	 */
    public function action_get_server_updated_times() {
    	$response = Api::get_server_updated_times();
    	$json = View::factory('json/display', Json::response('OK', 'get_server_updated_times', $response))->render();
    	$this->request->response = $json;
    }
    
    
    /**
	 * action_get_app_config()
	 *
	 * Get config for 'application' config
	 * TO DEPRECATE
	 *
	 */
    public function action_get_app_config() {
    	$config = ORM::factory('config')->where('label', '=', 'application')->find();
    	if($config->loaded()) {
    		// content is already in json in db, so decode
    		// and then re-encode in view
    		$response = array('config'=>json_decode($config->content));
    		$json = View::factory('json/display', Json::response('OK', 'get_app_config', $response))->render();
    		$this->request->response = $json;
    	}
    	else {
    		$json = View::factory('json/display', Json::response('ERR', 'no config found'))->render();
			$this->request->response = $json;
    	}
    }
    
    
    /**
	 * action_get_form_config()
	 *
	 * Get config for each form
	 *
	 */
    public function action_get_form_config() {
    	$forms = ORM::factory('form')->where('archived','=',0)->find_all();
    	$response = array();
    	foreach($forms as $form) {
    		$response[] = $form->get_config();
    		
    	}
    	$json = Json::response_array('OK', 'get_form_config', $response, 'config', 'forms');
    	$this->request->response = $json;
    }
    
    
    /**
	 * action_get_media_files()
	 *
	 * Get list of media to download
	 * TO DEPRECATE
	 */
     public function action_get_media_files() {
    
    	$last_server_update = Sdcard::get_last_server_update();
	  	$responseA['last_server_upd'] = $last_server_update;
	
		if ($last_server_update != Arr::get($_POST, 'last_server_upd')) {
			$medias = ORM::factory('media')->find_all();
			$files_arr = array();
			foreach ($medias as $media) {
				if ($media->file->loaded()) {
					$files_arr[] = $media->file->api_array();
				}
			}
			$responseA['files'] = $files_arr;			
		} 
    	$json = View::factory('json/display', Json::response('OK', 'get_media_files', $responseA))->render();
		$this->request->response = $json;
		
    }
    
    
    /**
	 * action_get_media()
	 *
	 * Get list of media to download
	 *
	 */
     public function action_get_media() {
    
    	$last_server_update = Sdcard::get_last_server_update();
    	$response = array();
	  	$response['last_server_upd'] = $last_server_update;
	
		if ($last_server_update != Arr::get($_POST, 'last_server_upd')) {
		
			$language = Arr::get($_POST, 'language', 'en');
			$medias = ORM::factory('media')->where('language','=',$language)->find_all();
			
			$response['media'] = array();
			foreach ($medias as $media) {
				$response['media'][] = $media->api_array();
			}		
		} 
    	//$json = Json::response_array('OK', 'get_media', $response, 'media');
    	$json = View::factory('json/display', Json::response('OK', 'get_media', $response))->render();
		$this->request->response = $json;
		
    }
///////////// Upload form data ///////////////////    
    
	/*
	 * Upload form data from phone
	 * Behaviour is different depending on the application type
	 * 
	 */
	function action_upload_form_data() {

		$form = $this->_preprocess_request();	

		if ($form == NULL)
			return;

		//get application_type
  	$app_type = ORM::factory('config')
  	            ->where('label','=',Kohana::config('values.application_type'))
  	            ->and_where('type','=',Kohana::config('values.platform'))
  	            ->find();

		if($app_type->loaded() AND $app_type->content)
		{		
			$patient_code = $this->_get_patient_code($app_type->content);
			$form_data = $this->_get_form_data($app_type->content,$form->id);

			if ($patient_code < 0 OR $form_data == NULL)
				return;

		} else 
		{
			$json = View::factory('json/display', Json::response('ERR', Kohana::config('errors.config_missing')))->render();
			$this->request->response = $json;
			return;
		}

		//common: Update form data values
		$form_data->household_code = Arr::get($_POST,"household_code");
		$form_data->patient_code = $patient_code;
		$form_data->creator_phone_id = $this->phone->id;
		$form_data->form_id = $form->id;
		$form_data->uploader_phone_id = $this->phone->id;
		$form_data->xml_content = Arr::get($_POST,"xml_content");
		$form_data->file_path = Arr::get($_POST,"file_path");
		$form_data->last_modified = Arr::get($_POST,"last_modified");
		
    /*
    Save household and patient data if necessary
    */
		if (($app_type->content == Kohana::config('values.app_type_households')) OR 
				($app_type->content == Kohana::config('values.app_type_patients_only')))
		{
			$this->_process_extra_data($form->code,$form_data);
		}

		//common: Insert or update the form data as the case may be; return form_data_id so devices can use it...
		if ($form_data->save()) {
			$json = View::factory('json/display', Json::response('OK', 'data_uploaded', array('form_data_id'=>$form_data->id)))->render();
		} 
		else {
			$json = View::factory('json/display', Json::response('ERR', 'affected=0'))->render();
		}
		$this->request->response = $json;
	}


	private function _preprocess_request() {
    if(! Arr::get($_POST,"xml_content")) {
      $json = View::factory('json/display', Json::response('ERR', 'xml_content is empty'))->render();
      $this->request->response = $json;
      return NULL;
    }

    $form = ORM::factory('form')->where('code','=',Arr::get($_POST,"form_code"))->find();
    if(! $form->loaded()) {
      $json = View::factory('json/display', Json::response('ERR', Kohana::config('errors.invalid_fcode')))->render();
      $this->request->response = $json;
      return NULL;
    }
		return $form;
	}

	private function _get_patient_code($app_type='') {

		if (($app_type == Kohana::config('values.app_type_households')) OR
        ($app_type == Kohana::config('values.app_type_patients_only')))
    {
      //1.- get patient_code from request
      $patient_code = Arr::get($_POST,"patient_code");

		} /*TODO be sure patients are created on the back-end prior to upload any data 
//		elseif ($app_type == Kohana::config('values.app_type_forms_only'))
		{
			//1.- get patient_code from phone id
			$patient = ORM::factory('patient')
							->where('register_phone_id','=',$this->phone->id)
							->find();

			if(! $patient->loaded()) 
			{
				$json = View::factory('json/display', Json::response('ERR', 'no corresponding study id found'))->render();
				$this->request->response = $json;
				return NULL;
			} else 
			{
				$patient_code = $patient->code;
			}
		} */
			else
		{
			$json = View::factory('json/display', Json::response('ERR', Kohana::config('errors.wrong_app_type')))->render();
			$this->request->response = $json;
			return -1;
		}

		return $patient_code;
	}


	private function _get_form_data($app_type='',$form_id=-1) {

		if (($app_type == Kohana::config('values.app_type_households')) OR
        ($app_type == Kohana::config('values.app_type_patients_only')))
    {
			$form_data = Model_Form_Data::get_by_key_data(
									Arr::get($_POST,"household_code",''),
									Arr::get($_POST,"patient_code",''),
									$form_id
									);
		}/*TODO be sure patients are created on the back-end prior to upload any data 
		// elseif ($app_type == Kohana::config('values.app_type_forms_only'))
		{
			$form_data = ORM::factory('form_data');
		}*/
		 else
		{
			$json = View::factory('json/display', Json::response('ERR', Kohana::config('errors.wrong_app_type')))->render();
			$this->request->response = $json;
			return NULL;
		}

		return $form_data;
	}

	private function _process_extra_data($form_code='',$form_data) {

    if($form_code=='hcore') {
      $household = Model_Household::save_from_form_data($form_data);
    }
    if($form_code=='pcore') {
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
                      'upload::size'=>array('32M')
                      ));
        if ($validation->check()){
          $patient->save_profile_image($_FILES['image']);
        }
      }
    }
	}
///////////// Upload form data ///////////////////    


	/**
	 * action_upload_form_data_file()
	 *
	 * 
	 * Upload file connected to form data, creating a record in the form_data_file table.
	 * unique key is form_data_file and xpath's node to the xform question.
	 * params: form_data_id, filename, data (filebody), type, xpath, last_modified
	 */
 	function action_upload_form_data_file() {
		
		//check input parameters 
    $fd = ORM::factory('form_data')->where('id','=',Arr::get($_POST,"form_data_id"))->find();
		if(! $fd->loaded()) 
		{
			$this->request->response = View::factory('json/display', Json::response('ERR', Kohana::config('errors.invalid_fdata_id')))->render();
			return;
		}
		
    if(! Arr::get($_POST,"xpath") AND !Arr::get($_POST,"type") == Kohana::config('values.type_signature')) {
			$this->request->response = View::factory('json/display', Json::response('ERR', Kohana::config('errors.empty_xpath')))->render();
			return;
		}

		//get or create form_data_file
		$fdf = $this->get_form_data_file(Arr::get($_POST,"form_data_id"),Arr::get($_POST,"xpath"),Arr::get($_POST,"type"));

		if (file_exists($fdf->filename))
		{
			unlink($fdf->filename);
		}

		//upload file & save data
  	if(isset($_FILES['data']))
		{
			//1.- validate
			$validation = Validate::factory($_FILES)
				->rules('data', array(
										'upload::valid'=>NULL, 
										//TODO define allowed file types
										'upload::type'=>array(array('3gp','mp4','m4a','aac','flac','mp3','ogg','wav','jpg','gif','bmp','webm','png')), 
										'upload::size'=>array('32M')
										));
			if (! $validation->check())
			{
				$this->request->response = View::factory('json/display', Json::response('ERR', Kohana::config('errors.wrong_file')))->render();
				return;
			}

			//2.- save file
			if($fdf->save_file($_FILES['data'], $fdf->get_fdf_path($fd).Arr::get($_POST,"filename")))
			{
				//fill the object
				$fdf->form_data_id = Arr::get($_POST,"form_data_id");
				$fdf->filename = $fdf->get_fdf_path($fd).Arr::get($_POST,"filename");
				$fdf->type = Arr::get($_POST,"type");
				$fdf->xpath = Arr::get($_POST,"xpath");
				$fdf->last_modified = Arr::get($_POST,"last_modified");

				if($fdf->save())
				{
					$this->request->response = View::factory('json/display', Json::response('OK', 'file saved'))->render();
					return;
				}
				else 
				{
					$this->request->response = View::factory('json/display', Json::response('ERR', Kohana::config('errors.error_saving_fdf')))->render();
					return;
				}
			}
			else
			{
				$this->request->response = View::factory('json/display', Json::response('ERR', Kohana::config('errors.error_getting_fdf')))->render();
				return;
			}
		}
	}
    
	//get existent form_data_file, based on key (form_data_id,xpath), or create a new one
	private function get_form_data_file($form_data_id=-1, $xpath='', $type='') {
		//case 'signature' is slightly different, since a form might have several signatures associated (a question can't)
		//right now, always create a new entry, without updating. TODO: create a way to update, using filename (would that be unique enough?)
		if ($type == Kohana::config('values.type_signature')) 
		{
			return ORM::factory('form_data_file');
		} 
		else 
		{
	 		$fdf = ORM::factory('form_data_file')
    	     ->where('form_data_id','=', $form_data_id)
    	     ->and_where('xpath','=', $xpath)
    	     ->find();

			if($fdf->loaded())
				return $fdf;
			else
				return ORM::factory('form_data_file');
		}
	}
	/**
	 * action_upload_form_file()
	 *
	 * TO DEPRECATE? (pau: used only in test/api.php; also new form_data_file table has been added..)
	 * Upload file connected to form data
	 *
	 */
    function action_upload_form_file() {
    
    	// load associated form
    	$form = ORM::factory('form')->where('code','=',Arr::get($_POST,"form_code"))->find();
		if(! $form->loaded()) {
			$json = View::factory('json/display', Json::response('ERR', Kohana::config('errors.invalid_fcode')))->render();
			$this->request->response = $json;
			return;
		}
		
		// load associated form data
		$form_data = Model_Form_Data::get_by_key_data(
							Arr::get($_POST,"household_code",''),
							Arr::get($_POST,"patient_code",''),
							$form->id
							);
		if(! $form_data->loaded()) {
			$json = View::factory('json/display', Json::response('ERR', 'invalid household or patient code'))->render();
			$this->request->response = $json;
			return;
		}
    	
    	// validate and save file
    	if(isset($_FILES['file'])){
			$validation = Validate::factory($_FILES)
				->rules('file', array(
										'upload::valid'=>NULL, 
										'upload::type'=>array(array('3gp','mp4','m4a','aac','flac','mp3','ogg','wav','jpg','gif','bmp','webm')), 
										'upload::size'=>array('32M')
										));
			if (! $validation->check()){
				$json = View::factory('json/display', Json::response('ERR', 'invalid file'))->render();
				$this->request->response = $json;
				return;
			}
			else {
				if (! $form_data->save_file($_FILES['file'])) {
					$json = View::factory('json/display', Json::response('ERR', 'failed to save file'))->render();
					$this->request->response = $json;
					return;
				}
			}
		}
    	$json = View::factory('json/display', Json::response('OK', 'file uploaded'))->render();
    	$this->request->response = $json;
    }
	
	
	
	/**
	 * action_upload_form_file()
	 *
	 * Upload list of phone locations
	 *
	 */
    function action_upload_phone_locations() {
    
		
		$validation = Validate::factory($_FILES)
					->rules('data', array(
											'upload::not_empty'=>NULL,
											'upload::valid'=>NULL
											));
		if (! $validation->check()){
			$json = View::factory('json/display', Json::response('ERR', 'no data file'))->render();
			$this->request->response = $json;
			return;
		}
			
		
		$phone = $this->phone;
		//echo $_FILES['data']['tmp_name'];
		$count = $phone->save_locations($_FILES['data']['tmp_name']);
		
		if ($count) {
			
			$json = View::factory('json/display', Json::response('OK', 'data_uploaded: '.$count.' rows'))->render();
		} 
		else {
			$json = View::factory('json/display', Json::response('ERR', 'affected=0'))->render();
		}
			
		//echo View::factory('profiler/stats');
		$this->request->response = $json;
		
	}
	
	
	/**
	 * action_register_gcm()
	 *
	 * Register phone's gcm registration id
	 *
	 */
    function action_register_alerts_id() {

		if(! $reg = trim(Arr::get($_POST,"registration_id",''))) {
			$json = View::factory('json/display', Json::response('ERR', 'registration_id is empty'))->render();
			$this->request->response = $json;
			return;
		}
		
		$phone = $this->phone;
		$phone->alerts_id = $reg;
		
		if($phone->save()) {
			$json = View::factory('json/display', Json::response('OK', 'registration id saved'))->render();
		} 
		else {
			$json = View::factory('json/display', Json::response('ERR', 'failed to save'))->render();
		}

		$this->request->response = $json;
		
	}
	
	
	/**
	 * action_confirm_alert()
	 *
	 * Confirm receipt of alert
	 * TO DEPRECATE
	 *
	 */
    function action_confirm_alert() {

		if(! $id = trim(Arr::get($_POST,"alert_id",''))) {
			$json = View::factory('json/display', Json::response('ERR', 'alert_id is empty'))->render();
			$this->request->response = $json;
			return;
		}
		
		$alert = ORM::factory('phone_alert', $id);
		if(! $alert->loaded() || $alert->phone_id!=$this->phone->id) {
			$json = View::factory('json/display', Json::response('ERR', 'alert_id invalid'))->render();
			$this->request->response = $json;
			return;
		}
		
		$alert->received = 1;
		if($alert->save()) {
			$json = View::factory('json/display', Json::response('OK', 'alert confirmed'))->render();
		} 
		else {
			$json = View::factory('json/display', Json::response('ERR', 'failed to save'))->render();
		}

		$this->request->response = $json;
		
	}

	/*
	* action_get_contacts()
	*
	*	returns a list of contacts associated to the given form, together to the common to all phones contacts (those with public=1)
	*/
	public function action_get_contacts() {
		$phone = $this->phone;
		$response = array();
		//get contacts by phone
		$pcontacts = ORM::factory('phone_contact')->where('phone_id', '=', $phone->id)->find_all();
		foreach($pcontacts as $pcontact) {
			$contact = ORM::factory('contact')->where('id','=',$pcontact->contact_id)->find();
			if ($contact->loaded()) {
				$response[] = $contact->get_contact();
			}
		}

		//get public contacts
		$pub_contacts =  ORM::factory('contact')->where('public','=',1)->find_all();
		foreach($pub_contacts as $pub_contact) {
				$response[] = $pub_contact->get_contact();
		}

		//build response
		$json = Json::response_array('OK', 'get_contacts', $response, 'data', 'contacts');
		$this->request->response = $json;
	}


	/*
	* action_get_contact_details()
	*
	*	returns details of a given contact
	*/
	public function action_get_contact_details() {
		$phone = $this->phone;

		if(! $cid = trim(Arr::get($_POST,"contact_id",''))) {
			$json = View::factory('json/display', Json::response('ERR', 'contact_id is empty'))->render();
			$this->request->response = $json;
			return;
		}

		$contact = ORM::factory('contact')->where('id','=',$cid)->find();
		if ($contact->loaded()) {
			$response = array();
			$response[] = $contact->get_contact();
			$json = View::factory('json/display', Json::response('OK', 'get_contact_details', $response))->render();
			$this->request->response = $json;
		}	else {
			//contact does not exist!
			$json = View::factory('json/display', Json::response('ERR', 'contact_id does not exist'))->render();
			$this->request->response = $json;
		}

	}
}
