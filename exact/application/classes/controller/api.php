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
	
	
	

}