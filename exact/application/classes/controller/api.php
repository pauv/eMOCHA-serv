<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Emocha_Controller_Api {


    
	/*
	 * Upload form data from phone
	 * override core class
	 * since there is no patient_code or household_code supplied
	 * just the phone imei and the form data.
	 */
    function action_upload_form_data() {
    
		if(! Arr::get($_POST,"xml_content")) {
			$json = View::factory('json/display', Json::response('ERR', 'xml_content is empty'))->render();
			$this->request->response = $json;
			return;
		}
		
		$form = ORM::factory('form')->where('code','=',Arr::get($_POST,"form_code"))->find();
		if(! $form->loaded()) {
			$json = View::factory('json/display', Json::response('ERR', 'invalid form code'))->render();
			$this->request->response = $json;
			return;
		}

		// load the study/patient from the phone id
		$patient = ORM::factory('patient')
						->where('phone_id','=',$this->phone->id)
						->find();
		if(! $patient->loaded()) {
			$json = View::factory('json/display', Json::response('ERR', 'no corresponding study id found'))->render();
			$this->request->response = $json;
			return;
		}
		
		
		
		/*
		Update form data values
		*/
		$form_data = ORM::factory('form_data');
		
		$form_data->patient_code = $patient->code;
		$form_data->creator_phone_id = $this->phone->id;
		$form_data->form_id = $form->id;
		$form_data->uploader_phone_id = $this->phone->id;
		$form_data->xml_content = Arr::get($_POST,"xml_content");
		$form_data->last_modified = Arr::get($_POST,"last_modified");
		//$form_data->notified = Arr::get($_POST,"pn_ts");
		
		// check time limits
		/*
		if($form->code=='erandom') {
			$delay_limit_config = ORM::factory('config')->where('label','=','form_reminder_delay_interval')->find();
			if($delay_limit_config->loaded() && $delay_limit_config->content) {
				// compare last_modified and pn_ts
				$notified = strtotime($form_data->notified);
				$modified = strtotime($form_data->last_modified);
				$diff = $modified-$notified;
				if($diff > $delay_limit_config->content) {
					$form_data->rejected = 'late';
				}
			}
		}
		*/
		
		
		/*
		Insert or update the form data as the case may be
		*/
		if ($form_data->save()) {
			
			$json = View::factory('json/display', Json::response('OK', 'data_uploaded'))->render();
		} 
		else {
			$json = View::factory('json/display', Json::response('ERR', 'affected=0'))->render();
		}
			
		//echo View::factory('profiler/stats');
		$this->request->response = $json;
		
	}
	
	/*
	 * Log form reminder generated in phone
	 * find connected form data and update if it was late or not
	 *
	 * POST vars sent: patient_id, reminder_id, reminder_ts, reply_ts, form_code, last_modified
	 */
	public function action_log_reminder() {
		
		$form = ORM::factory('form')->where('code','=',Arr::get($_POST,"form_code"))->find();
		if(! $form->loaded()) {
			$json = View::factory('json/display', Json::response('ERR', 'invalid form code'))->render();
			$this->request->response = $json;
			return;
		}

		// load the study/patient from the phone id
		$patient = ORM::factory('patient')
						->where('phone_id','=',$this->phone->id)
						->find();
		if(! $patient->loaded()) {
			$json = View::factory('json/display', Json::response('ERR', 'no corresponding study id found'))->render();
			$this->request->response = $json;
			return;
		}
		
		
		// check if form reminder already logged
		$phone_form_reminder = ORM::factory('phone_form_reminder')
							->where('phone_id','=',$this->phone->id)
							->and_where('reminder_id','=',Arr::get($_POST,"reminder_id"))
							->find();
		if($phone_form_reminder->loaded()) {
			$json = View::factory('json/display', Json::response('ERR', 'reminder already logged'))->render();
			$this->request->response = $json;
			return;
		}
		
		
		/*
		if erandom, then find data and check response time
		*/
		if($form->code=='erandom') {
		
			// find form data
			$form_data = ORM::factory('form_data')
						->where('form_id','=',$form->id)
						->and_where('last_modified','=',Arr::get($_POST,"last_modified"))
						->find();
			if(! $form_data->loaded()) {
				$json = View::factory('json/display', Json::response('ERR', 'no corresponding form data'))->render();
				$this->request->response = $json;
				return;
			}
		
			// check time limits
			$delay_limit_config = ORM::factory('config')->where('label','=','form_reminder_delay_interval')->find();
			if($delay_limit_config->loaded() && $delay_limit_config->content) {
				// compare last_modified and pn_ts
				$notified = strtotime(Arr::get($_POST,"reminder_ts"));
				$modified = strtotime(Arr::get($_POST,"last_modified"));
				$diff = $modified-$notified;
				if($diff > $delay_limit_config->content) {
					$form_data->rejected = 'late';
				}
			}
			
			$form_data->notified = Arr::get($_POST,"reminder_ts");
			$form_data->save();
			
		}
		
		
		
		
		/*
		Add new record
		*/
		$phone_form_reminder->phone_id = $this->phone->id;
		$phone_form_reminder->form_id = $form->id;
		$phone_form_reminder->reminder_id = Arr::get($_POST,"reminder_id");
		$phone_form_reminder->reminder_ts = Arr::get($_POST,"reminder_ts");
		$phone_form_reminder->reply_ts = Arr::get($_POST,"reply_ts");
		$phone_form_reminder->last_modified = Arr::get($_POST,"last_modified");
		
		/*
		Insert the reminder
		*/
		if ($phone_form_reminder->save()) {
			$json = View::factory('json/display', Json::response('OK', 'data_uploaded'))->render();
		} 
		else {
			$json = View::factory('json/display', Json::response('ERR', 'affected=0'))->render();
		}
		
		$this->request->response = $json;
	}
	
	

}