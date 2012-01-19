<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Emocha_Controller_Api {

	protected $phone;
	
	public function before()
	{
		//parent::before();
		
		switch($this->request->action) {
			case 'activate_phone':
				// no user check required
			break;
			case 'get_media':
				// no user check required
			break;
			
			default:
				// check user and update gps / connect time info
				if(Kohana::config('api.authentication')=='usr_only'){
					$this->phone = Phone::get_by_user(Arr::get($_POST, 'usr')); 
					//echo Kohana::debug($this->phone);
				}
				else {
					$this->phone = Phone::get_by_user_password(Arr::get($_POST, 'usr'), Arr::get($_POST, 'pwd')); 
				}
				if ( ! $this->phone) {
					$json = View::factory('json/display', Json::response('ERR', 'unknown user'))->render();
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
	
	
  /*
   * Activate phone with auto-validation
   *
   */
  public function action_activate_phone() {
    	$imei = preg_replace('/\W/', '', Arr::get($_POST, 'imei', ''));    	
    	// auto validate
    	$result = Phone::activate($imei, TRUE);
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
   * Register language of phone
   *
   */
  public function action_register_language() {
  
  		// check a language has been submitted
    	if(! $language = trim(Arr::get($_POST,"language",''))) {
			$json = View::factory('json/display', Json::response('ERR', 'language variable is empty'))->render();
			$this->request->response = $json;
			return;
		}
		
		// check languages have been configured on the server
		$languages = Kohana::config('language.languages');
		if(!is_array($languages) || !sizeof($languages)) {
			$json = View::factory('json/display', Json::response('ERR', 'no languages in server config'))->render();
			$this->request->response = $json;
			return;
		}
		
		// check language exists in config
		$languages = Kohana::config('language.languages');
		if(!array_key_exists($language, $languages)) {
			$json = View::factory('json/display', Json::response('ERR', 'submitted language does not exist in config'))->render();
			$this->request->response = $json;
			return;
		}
		
		// save language to phone record
		$phone = $this->phone;
		$phone->language = $language;
		
		if($phone->save()) {
			$json = View::factory('json/display', Json::response('OK', 'language saved'))->render();
		} 
		else {
			$json = View::factory('json/display', Json::response('ERR', 'failed to save'))->render();
		}

		$this->request->response = $json;
		
	}
	
	
	
	

}